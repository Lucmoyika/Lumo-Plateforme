<?php

namespace App\Modules\Education\Ecoles\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct(private readonly StudentService $studentService) {}

    public function index(Request $request): JsonResponse
    {
        $schoolId = (int) $request->get('school_id');

        if ($schoolId) {
            $paginator = $this->studentService->listBySchool($schoolId, (int) $request->get('per_page', 15));
            return $this->paginatedResponse($paginator, 'Élèves récupérés.');
        }

        $paginator = $this->studentService->paginate((int) $request->get('per_page', 15), ['user', 'class']);
        return $this->paginatedResponse($paginator, 'Élèves récupérés.');
    }

    public function show(int $id): JsonResponse
    {
        $student = $this->studentService->getById($id, ['user', 'class', 'school']);

        if (!$student) {
            return $this->errorResponse('Élève introuvable.', [], 404);
        }

        return $this->successResponse($student, 'Élève récupéré.');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id'          => ['required', 'exists:users,id'],
            'school_id'        => ['required', 'exists:schools,id'],
            'class_id'         => ['nullable', 'exists:school_classes,id'],
            'student_number'   => ['nullable', 'string', 'max:50'],
            'enrollment_date'  => ['nullable', 'date'],
            'parent_id'        => ['nullable', 'exists:users,id'],
            'guardian_name'    => ['nullable', 'string', 'max:100'],
            'guardian_phone'   => ['nullable', 'string', 'max:20'],
        ]);

        $student = $this->studentService->create($data);

        return $this->successResponse($student, 'Élève créé.', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'class_id'       => ['nullable', 'exists:school_classes,id'],
            'guardian_name'  => ['nullable', 'string', 'max:100'],
            'guardian_phone' => ['nullable', 'string', 'max:20'],
            'status'         => ['nullable', 'string', 'in:active,inactive,graduated,transferred'],
        ]);

        $student = $this->studentService->update($id, $data);

        return $this->successResponse($student, 'Élève mis à jour.');
    }

    public function destroy(int $id): JsonResponse
    {
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
