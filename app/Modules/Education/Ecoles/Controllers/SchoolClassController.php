<?php

namespace App\Modules\Education\Ecoles\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\Services\SchoolClassService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function __construct(private readonly SchoolClassService $classService) {}

    public function index(Request $request): JsonResponse
    {
        $schoolId = (int) $request->get('school_id');
        if ($schoolId) {
            $classes = $this->classService->getBySchool($schoolId);
            return $this->successResponse($classes, 'Classes récupérées.');
        }

        $paginator = $this->classService->paginate((int) $request->get('per_page', 15));
        return $this->paginatedResponse($paginator, 'Classes récupérées.');
    }

    public function show(int $id): JsonResponse
    {
        $class = $this->classService->getById($id, ['school', 'teacher', 'students']);

        if (!$class) {
            return $this->errorResponse('Classe introuvable.', [], 404);
        }

        return $this->successResponse($class, 'Classe récupérée.');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'school_id'   => ['required', 'exists:schools,id'],
            'name'        => ['required', 'string', 'max:100'],
            'level'       => ['required', 'string', 'max:50'],
            'teacher_id'  => ['nullable', 'exists:teachers,id'],
            'capacity'    => ['nullable', 'integer', 'min:1'],
            'school_year' => ['required', 'string', 'max:20'],
        ]);

        $class = $this->classService->create($data);

        return $this->successResponse($class, 'Classe créée.', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name'       => ['sometimes', 'string', 'max:100'],
            'level'      => ['sometimes', 'string', 'max:50'],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
            'capacity'   => ['nullable', 'integer', 'min:1'],
        ]);

        $class = $this->classService->update($id, $data);

        return $this->successResponse($class, 'Classe mise à jour.');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->classService->delete($id);

        return $this->successResponse(null, 'Classe supprimée.');
    }

    /**
     * Manage schedule for a class.
     */
    public function updateSchedule(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'schedules'            => ['required', 'array'],
            'schedules.*.day'      => ['required', 'string', 'in:monday,tuesday,wednesday,thursday,friday,saturday'],
            'schedules.*.start'    => ['required', 'date_format:H:i'],
            'schedules.*.end'      => ['required', 'date_format:H:i', 'after:schedules.*.start'],
            'schedules.*.subject'  => ['required', 'string', 'max:100'],
            'schedules.*.teacher_id' => ['nullable', 'exists:teachers,id'],
        ]);

        $class = $this->classService->getById($id);
        if (!$class) {
            return $this->errorResponse('Classe introuvable.', [], 404);
        }

        $class->schedules()->delete();
        foreach ($data['schedules'] as $schedule) {
            $class->schedules()->create($schedule);
        }

        return $this->successResponse($class->load('schedules'), 'Emploi du temps mis à jour.');
    }
}
