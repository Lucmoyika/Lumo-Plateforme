<?php

namespace App\Modules\Education\Universites\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Universites\Services\UniversityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function __construct(private readonly UniversityService $universityService) {}

    public function index(Request $request): JsonResponse
    {
        $filters   = $request->only(['city', 'status', 'search']);
        $paginator = $this->universityService->list($filters, (int) $request->get('per_page', 15));

        return $this->paginatedResponse($paginator, 'Universités récupérées.');
    }

    public function show(int $id): JsonResponse
    {
        $university = $this->universityService->getById($id, ['rector', 'faculties']);

        if (!$university) {
            return $this->errorResponse('Université introuvable.', [], 404);
        }

        return $this->successResponse($university, 'Université récupérée.');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'acronym'     => ['nullable', 'string', 'max:20'],
            'address'     => ['required', 'string', 'max:255'],
            'city'        => ['required', 'string', 'max:100'],
            'country'     => ['nullable', 'string', 'max:100'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'email'       => ['nullable', 'email'],
            'website'     => ['nullable', 'url'],
            'rector_id'   => ['nullable', 'exists:users,id'],
            'status'      => ['nullable', 'string', 'in:active,inactive'],
            'description' => ['nullable', 'string'],
        ]);

        $university = $this->universityService->create($data);

        return $this->successResponse($university, 'Université créée.', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name'        => ['sometimes', 'string', 'max:255'],
            'acronym'     => ['nullable', 'string', 'max:20'],
            'address'     => ['sometimes', 'string', 'max:255'],
            'city'        => ['sometimes', 'string', 'max:100'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'email'       => ['nullable', 'email'],
            'website'     => ['nullable', 'url'],
            'rector_id'   => ['nullable', 'exists:users,id'],
            'status'      => ['nullable', 'string', 'in:active,inactive'],
            'description' => ['nullable', 'string'],
        ]);

        $university = $this->universityService->update($id, $data);

        return $this->successResponse($university, 'Université mise à jour.');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->universityService->delete($id);

        return $this->successResponse(null, 'Université supprimée.');
    }
}
