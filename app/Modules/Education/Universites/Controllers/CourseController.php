<?php

namespace App\Modules\Education\Universites\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Universites\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(private readonly CourseService $courseService) {}

    public function index(Request $request): JsonResponse
    {
        if ($departmentId = $request->get('department_id')) {
            $courses = $this->courseService->getByDepartment((int) $departmentId);
            return $this->successResponse($courses, 'Cours récupérés.');
        }

        $paginator = $this->courseService->paginate((int) $request->get('per_page', 15));
        return $this->paginatedResponse($paginator, 'Cours récupérés.');
    }

    public function show(int $id): JsonResponse
    {
        $course = $this->courseService->getById($id, ['department', 'teacher']);

        if (!$course) {
            return $this->errorResponse('Cours introuvable.', [], 404);
        }

        return $this->successResponse($course, 'Cours récupéré.');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'name'          => ['required', 'string', 'max:255'],
            'code'          => ['nullable', 'string', 'max:20'],
            'credits'       => ['required', 'integer', 'min:1', 'max:30'],
            'semester'      => ['required', 'integer', 'min:1'],
            'teacher_id'    => ['nullable', 'exists:users,id'],
            'description'   => ['nullable', 'string'],
            'is_mandatory'  => ['nullable', 'boolean'],
        ]);

        $course = $this->courseService->create($data);

        return $this->successResponse($course, 'Cours créé.', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name'         => ['sometimes', 'string', 'max:255'],
            'code'         => ['nullable', 'string', 'max:20'],
            'credits'      => ['sometimes', 'integer', 'min:1'],
            'teacher_id'   => ['nullable', 'exists:users,id'],
            'description'  => ['nullable', 'string'],
            'is_mandatory' => ['nullable', 'boolean'],
        ]);

        $course = $this->courseService->update($id, $data);

        return $this->successResponse($course, 'Cours mis à jour.');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->courseService->delete($id);

        return $this->successResponse(null, 'Cours supprimé.');
    }
}
