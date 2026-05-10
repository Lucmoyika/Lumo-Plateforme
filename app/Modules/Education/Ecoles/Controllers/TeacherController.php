<?php

namespace App\Modules\Education\Ecoles\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\Requests\TeacherRequest;
use App\Modules\Education\Ecoles\Services\TeacherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function __construct(private readonly TeacherService $teacherService) {}

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
            $paginator = $this->teacherService->listBySchool($schoolId, (int) $request->get('per_page', 15), $academicYear, $includeArchived);
            return $this->paginatedResponse($paginator, 'Enseignants récupérés.');
        }

        $paginator = $this->teacherService->paginate((int) $request->get('per_page', 15), ['user', 'school']);
        return $this->paginatedResponse($paginator, 'Enseignants récupérés.');
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $teacher = $this->teacherService->getById($id, ['user', 'school', 'classes']);

        if (!$teacher) {
            return $this->errorResponse('Enseignant introuvable.', [], 404);
        }

        $schoolId = (int) ($request->route('school') ?? 0);
        if ($schoolId > 0 && (int) $teacher->school_id !== $schoolId) {
            return $this->errorResponse('Enseignant hors périmètre établissement.', [], 403);
        }

        return $this->successResponse($teacher, 'Enseignant récupéré.');
    }

    public function store(TeacherRequest $request): JsonResponse
    {
        $schoolId = (int) ($request->route('school') ?? $request->get('school_id') ?? 0);

        $data = $request->validated();

        if ($schoolId > 0) {
            $data['school_id'] = $schoolId;
        }

        $teacher = $this->teacherService->create($data);

        return $this->successResponse($teacher, 'Enseignant créé.', 201);
    }

    public function update(TeacherRequest $request, int $id): JsonResponse
    {
        $existing = $this->teacherService->getById($id);
        if (!$existing) {
            return $this->errorResponse('Enseignant introuvable.', [], 404);
        }

        $schoolId = (int) ($request->route('school') ?? 0);
        if ($schoolId > 0 && (int) $existing->school_id !== $schoolId) {
            return $this->errorResponse('Enseignant hors périmètre établissement.', [], 403);
        }

        $data = $request->validated();

        $teacher = $this->teacherService->update($id, $data);

        return $this->successResponse($teacher, 'Enseignant mis à jour.');
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $existing = $this->teacherService->getById($id);
        if (!$existing) {
            return $this->errorResponse('Enseignant introuvable.', [], 404);
        }

        $schoolId = (int) ($request->route('school') ?? 0);
        if ($schoolId > 0 && (int) $existing->school_id !== $schoolId) {
            return $this->errorResponse('Enseignant hors périmètre établissement.', [], 403);
        }

        $this->teacherService->delete($id);

        return $this->successResponse(null, 'Enseignant supprimé.');
    }

    /**
     * Get weekly schedule for a teacher.
     */
    public function getSchedule(Request $request, int $id): JsonResponse
    {
        $schoolId = (int) ($request->route('school') ?? 0);
        $teacher = $this->teacherService->getById($id);
        if (!$teacher) {
            return $this->errorResponse('Enseignant introuvable.', [], 404);
        }

        if ($schoolId > 0 && (int) $teacher->school_id !== $schoolId) {
            return $this->errorResponse('Enseignant hors périmètre établissement.', [], 403);
        }

        $schedule = $this->teacherService->getSchedule($id);

        return $this->successResponse($schedule, 'Emploi du temps récupéré.');
    }

    /**
     * Get all classes assigned to a teacher.
     */
    public function getClasses(Request $request, int $id): JsonResponse
    {
        $schoolId = (int) ($request->route('school') ?? 0);
        $teacher = $this->teacherService->getById($id);
        if (!$teacher) {
            return $this->errorResponse('Enseignant introuvable.', [], 404);
        }

        if ($schoolId > 0 && (int) $teacher->school_id !== $schoolId) {
            return $this->errorResponse('Enseignant hors périmètre établissement.', [], 403);
        }

        $classes = $this->teacherService->getClasses($id);

        return $this->successResponse($classes, 'Classes récupérées.');
    }
}
