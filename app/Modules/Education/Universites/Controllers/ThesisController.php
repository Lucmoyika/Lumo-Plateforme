<?php

namespace App\Modules\Education\Universites\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Universites\Services\ThesisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ThesisController extends Controller
{
    public function __construct(private readonly ThesisService $thesisService) {}

    public function index(Request $request): JsonResponse
    {
        $filters   = $request->only(['status', 'student_id', 'search']);
        $paginator = $this->thesisService->list($filters, (int) $request->get('per_page', 15));

        return $this->paginatedResponse($paginator, 'Thèses récupérées.');
    }

    public function show(int $id): JsonResponse
    {
        $thesis = $this->thesisService->getById($id, ['student.user', 'supervisor']);

        if (!$thesis) {
            return $this->errorResponse('Thèse introuvable.', [], 404);
        }

        return $this->successResponse($thesis, 'Thèse récupérée.');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'student_id'    => ['required', 'exists:university_students,id'],
            'title'         => ['required', 'string', 'max:500'],
            'abstract'      => ['nullable', 'string'],
            'supervisor_id' => ['nullable', 'exists:users,id'],
            'keywords'      => ['nullable', 'array'],
            'file_path'     => ['nullable', 'string'],
            'submitted_at'  => ['nullable', 'date'],
        ]);

        $thesis = $this->thesisService->create($data);

        return $this->successResponse($thesis, 'Thèse créée.', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'title'         => ['sometimes', 'string', 'max:500'],
            'abstract'      => ['nullable', 'string'],
            'supervisor_id' => ['nullable', 'exists:users,id'],
            'keywords'      => ['nullable', 'array'],
            'file_path'     => ['nullable', 'string'],
        ]);

        $thesis = $this->thesisService->update($id, $data);

        return $this->successResponse($thesis, 'Thèse mise à jour.');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->thesisService->delete($id);

        return $this->successResponse(null, 'Thèse supprimée.');
    }

    /**
     * Update the status of a thesis (pending, approved, rejected, defended).
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'status'   => ['required', 'string', 'in:pending,under_review,approved,rejected,defended'],
            'feedback' => ['nullable', 'string'],
        ]);

        $thesis = $this->thesisService->updateStatus($id, $data['status'], $data['feedback'] ?? null);

        return $this->successResponse($thesis, 'Statut de la thèse mis à jour.');
    }
}
