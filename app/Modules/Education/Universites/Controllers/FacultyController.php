<?php

namespace App\Modules\Education\Universites\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Universites\Services\FacultyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function __construct(private readonly FacultyService $facultyService) {}

    public function index(Request $request): JsonResponse
    {
        if ($universityId = $request->get('university_id')) {
            $faculties = $this->facultyService->getByUniversity((int) $universityId);
            return $this->successResponse($faculties, 'Facultés récupérées.');
        }

        $paginator = $this->facultyService->paginate((int) $request->get('per_page', 15));
        return $this->paginatedResponse($paginator, 'Facultés récupérées.');
    }

    public function show(int $id): JsonResponse
    {
        $faculty = $this->facultyService->getById($id, ['university', 'departments', 'dean']);

        if (!$faculty) {
            return $this->errorResponse('Faculté introuvable.', [], 404);
        }

        return $this->successResponse($faculty, 'Faculté récupérée.');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'university_id' => ['required', 'exists:universities,id'],
            'name'          => ['required', 'string', 'max:255'],
            'code'          => ['nullable', 'string', 'max:20'],
            'dean_id'       => ['nullable', 'exists:users,id'],
            'description'   => ['nullable', 'string'],
        ]);

        $faculty = $this->facultyService->create($data);

        return $this->successResponse($faculty, 'Faculté créée.', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name'        => ['sometimes', 'string', 'max:255'],
            'code'        => ['nullable', 'string', 'max:20'],
            'dean_id'     => ['nullable', 'exists:users,id'],
            'description' => ['nullable', 'string'],
        ]);

        $faculty = $this->facultyService->update($id, $data);

        return $this->successResponse($faculty, 'Faculté mise à jour.');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->facultyService->delete($id);

        return $this->successResponse(null, 'Faculté supprimée.');
    }
}
