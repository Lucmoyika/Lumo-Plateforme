<?php

namespace App\Modules\Education\Ecoles\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\Services\TeacherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function __construct(private readonly TeacherService $teacherService) {}

    public function index(Request $request): JsonResponse
    {
        $schoolId = (int) $request->get('school_id');

        if ($schoolId) {
            $paginator = $this->teacherService->listBySchool($schoolId, (int) $request->get('per_page', 15));
            return $this->paginatedResponse($paginator, 'Enseignants récupérés.');
        }

        $paginator = $this->teacherService->paginate((int) $request->get('per_page', 15), ['user', 'school']);
        return $this->paginatedResponse($paginator, 'Enseignants récupérés.');
    }

    public function show(int $id): JsonResponse
    {
        $teacher = $this->teacherService->getById($id, ['user', 'school', 'classes']);

        if (!$teacher) {
            return $this->errorResponse('Enseignant introuvable.', [], 404);
        }

        return $this->successResponse($teacher, 'Enseignant récupéré.');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id'          => ['required', 'exists:users,id'],
            'school_id'        => ['required', 'exists:schools,id'],
            'employee_number'  => ['nullable', 'string', 'max:50'],
            'subjects'         => ['nullable', 'array'],
            'qualification'    => ['nullable', 'string', 'max:255'],
            'experience_years' => ['nullable', 'integer', 'min:0'],
            'status'           => ['nullable', 'string', 'in:active,inactive,on_leave'],
        ]);

        $teacher = $this->teacherService->create($data);

        return $this->successResponse($teacher, 'Enseignant créé.', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'subjects'         => ['nullable', 'array'],
            'qualification'    => ['nullable', 'string', 'max:255'],
            'experience_years' => ['nullable', 'integer', 'min:0'],
            'status'           => ['nullable', 'string', 'in:active,inactive,on_leave'],
        ]);

        $teacher = $this->teacherService->update($id, $data);

        return $this->successResponse($teacher, 'Enseignant mis à jour.');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->teacherService->delete($id);

        return $this->successResponse(null, 'Enseignant supprimé.');
    }

    /**
     * Get weekly schedule for a teacher.
     */
    public function getSchedule(int $id): JsonResponse
    {
        $schedule = $this->teacherService->getSchedule($id);

        return $this->successResponse($schedule, 'Emploi du temps récupéré.');
    }

    /**
     * Get all classes assigned to a teacher.
     */
    public function getClasses(int $id): JsonResponse
    {
        $classes = $this->teacherService->getClasses($id);

        return $this->successResponse($classes, 'Classes récupérées.');
    }
}
