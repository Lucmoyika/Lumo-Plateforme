<?php

namespace App\Modules\Education\Universites\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Universites\Services\DepartmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct(private readonly DepartmentService $departmentService) {}

    public function index(Request $request): JsonResponse
    {
        if ($facultyId = $request->get('faculty_id')) {
            $departments = $this->departmentService->getByFaculty((int) $facultyId);
            return $this->successResponse($departments, 'Départements récupérés.');
        }

        $paginator = $this->departmentService->paginate((int) $request->get('per_page', 15));
        return $this->paginatedResponse($paginator, 'Départements récupérés.');
    }

    public function show(int $id): JsonResponse
    {
        $department = $this->departmentService->getById($id, ['faculty', 'courses', 'head']);

        if (!$department) {
            return $this->errorResponse('Département introuvable.', [], 404);
        }

        return $this->successResponse($department, 'Département récupéré.');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'faculty_id'  => ['required', 'exists:faculties,id'],
            'name'        => ['required', 'string', 'max:255'],
            'code'        => ['nullable', 'string', 'max:20'],
            'head_id'     => ['nullable', 'exists:users,id'],
            'description' => ['nullable', 'string'],
        ]);

        $department = $this->departmentService->create($data);

        return $this->successResponse($department, 'Département créé.', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name'        => ['sometimes', 'string', 'max:255'],
            'code'        => ['nullable', 'string', 'max:20'],
            'head_id'     => ['nullable', 'exists:users,id'],
            'description' => ['nullable', 'string'],
        ]);

        $department = $this->departmentService->update($id, $data);

        return $this->successResponse($department, 'Département mis à jour.');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->departmentService->delete($id);

        return $this->successResponse(null, 'Département supprimé.');
    }
}
