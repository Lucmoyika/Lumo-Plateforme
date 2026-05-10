<?php

namespace App\Modules\Education\Ecoles\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\Requests\StudentRequest;
use App\Modules\Education\Ecoles\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct(private readonly StudentService $studentService) {}

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'academic_year' => ['nullable', 'string', 'max:20'],
            'include_archived' => ['nullable', 'boolean'],
        ]);

        $schoolId = (int) ($request->route('school') ?? $request->get('school_id') ?? 0);
        $academicYear = $request->get('academic_year');
        $includeArchived = $request->boolean('include_archived', false);

        if ($schoolId) {
            $paginator = $this->studentService->listBySchool($schoolId, (int) $request->get('per_page', 15), $academicYear, $includeArchived);
            return $this->paginatedResponse($paginator, 'Élèves récupérés.');
        }

        $paginator = $this->studentService->paginate((int) $request->get('per_page', 15), ['user', 'class_']);
        return $this->paginatedResponse($paginator, 'Élèves récupérés.');
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $student = $this->studentService->getById($id, ['user', 'class_', 'school']);

        if (!$student) {
            return $this->errorResponse('Élève introuvable.', [], 404);
        }

        // Vérifier la propriété de l'école
        $schoolId = (int) ($request->route('school') ?? 0);
        if ($schoolId > 0 && (int) $student->school_id !== $schoolId) {
            return $this->errorResponse('Élève hors périmètre établissement.', [], 403);
        }

        // Vérifier les permissions avec la Policy
        $this->authorize('view', $student);

        return $this->successResponse($student, 'Élève récupéré.');
    }

    public function store(StudentRequest $request): JsonResponse
    {
        $schoolId = (int) ($request->route('school') ?? $request->get('school_id') ?? 0);

        // Vérifier que l'utilisateur peut créer un élève dans cette école
        if (!auth()->user()->can('students.create') && !auth()->user()->hasRole(['school_admin', 'school_staff'])) {
            return $this->errorResponse('Vous n\'avez pas la permission de créer un élève.', [], 403);
        }

        $data = $request->validated();

        if ($schoolId > 0) {
            $data['school_id'] = $schoolId;
        }

        if (!empty($data['class_id'])) {
            $class = \App\Modules\Education\Ecoles\Models\SchoolClass::query()->find($data['class_id']);
            if (!$class || (int) $class->school_id !== (int) $data['school_id']) {
                return $this->errorResponse('La classe sélectionnée ne correspond pas à cet établissement.', [], 422);
            }
        }

        $student = $this->studentService->create($data);

        return $this->successResponse($student, 'Élève créé.', 201);
    }

    public function update(StudentRequest $request, int $id): JsonResponse
    {
        $existing = $this->studentService->getById($id);
        if (!$existing) {
            return $this->errorResponse('Élève introuvable.', [], 404);
        }

        // Vérifier les permissions avec la Policy
        $this->authorize('update', $existing);

        $schoolId = (int) ($request->route('school') ?? 0);
        if ($schoolId > 0 && (int) $existing->school_id !== $schoolId) {
            return $this->errorResponse('Élève hors périmètre établissement.', [], 403);
        }

        $data = $request->validated();

        if (!empty($data['class_id'])) {
            $class = \App\Modules\Education\Ecoles\Models\SchoolClass::query()->find($data['class_id']);
            if (!$class || (int) $class->school_id !== (int) $existing->school_id) {
                return $this->errorResponse('La classe sélectionnée ne correspond pas à cet établissement.', [], 422);
            }
        }

        $student = $this->studentService->update($id, $data);

        return $this->successResponse($student, 'Élève mis à jour.');
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $existing = $this->studentService->getById($id);
        if (!$existing) {
            return $this->errorResponse('Élève introuvable.', [], 404);
        }

        // Vérifier les permissions avec la Policy
        $this->authorize('delete', $existing);

        $schoolId = (int) ($request->route('school') ?? 0);
        if ($schoolId > 0 && (int) $existing->school_id !== $schoolId) {
            return $this->errorResponse('Élève hors périmètre établissement.', [], 403);
        }

        $this->studentService->delete($id);

        return $this->successResponse(null, 'Élève supprimé.');
    }

    /**
     * Import students from a CSV file.
     */
    public function importStudents(Request $request): JsonResponse
    {
        $request->validate([
            'school_id' => ['required', 'exists:schools,id'],
            'file'      => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        try {
            $result = $this->studentService->importFromCsv(
                (int) $request->get('school_id'),
                $request->file('file')
            );

            return $this->successResponse($result, "Import terminé : {$result['created']} élèves créés.");
        } catch (\Throwable $e) {
            return $this->errorResponse('Erreur lors de l\'import : ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Get all grades for a student.
     */
    public function getGrades(int $id): JsonResponse
    {
        $grades = $this->studentService->getGrades($id);

        return $this->successResponse($grades, 'Notes récupérées.');
    }

    /**
     * Get the bulletin (report card) for a student and period.
     */
    public function getBulletin(Request $request, int $id): JsonResponse
    {
        $request->validate(['period' => ['required', 'string']]);

        $bulletin = $this->studentService->getBulletin($id, $request->get('period'));

        return $this->successResponse($bulletin, 'Bulletin récupéré.');
    }
}
