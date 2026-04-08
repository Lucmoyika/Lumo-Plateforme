<?php

namespace App\Modules\Education\Ecoles\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\Requests\SchoolRequest;
use App\Modules\Education\Ecoles\Services\SchoolService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function __construct(private readonly SchoolService $schoolService) {}

    /**
     * List schools with optional filters (type, city, status).
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['type', 'city', 'status', 'search']);
        $perPage = (int) $request->get('per_page', 15);

        $paginator = $this->schoolService->list($filters, $perPage);

        return $this->paginatedResponse($paginator, 'Écoles récupérées.');
    }

    /**
     * Show a single school.
     */
    public function show(int $id): JsonResponse
    {
        $school = $this->schoolService->getById($id, ['director', 'classes']);

        if (!$school) {
            return $this->errorResponse('École introuvable.', [], 404);
        }

        return $this->successResponse($school, 'École récupérée.');
    }

    /**
     * Create a new school.
     */
    public function store(SchoolRequest $request): JsonResponse
    {
        $school = $this->schoolService->create($request->validated());

        return $this->successResponse($school, 'École créée avec succès.', 201);
    }

    /**
     * Update an existing school.
     */
    public function update(SchoolRequest $request, int $id): JsonResponse
    {
        $school = $this->schoolService->update($id, $request->validated());

        return $this->successResponse($school, 'École mise à jour.');
    }

    /**
     * Soft-delete a school.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->schoolService->delete($id);

        return $this->successResponse(null, 'École supprimée.');
    }

    /**
     * Restore a soft-deleted school.
     */
    public function restore(int $id): JsonResponse
    {
        $school = $this->schoolService->schoolRepository->restore($id);

        return $this->successResponse($school, 'École restaurée.');
    }
}
