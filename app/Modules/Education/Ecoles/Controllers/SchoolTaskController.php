<?php

namespace App\Modules\Education\Ecoles\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Education\Ecoles\Models\School;
use App\Modules\Education\Ecoles\Models\Student;
use App\Modules\Education\Ecoles\Models\Teacher;
use App\Modules\Education\Ecoles\Requests\SchoolTaskRequest;
use App\Modules\Education\Ecoles\Services\SchoolTaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchoolTaskController extends Controller
{
    public function __construct(private readonly SchoolTaskService $schoolTaskService) {}

    public function index(Request $request, int $school): JsonResponse
    {
        $request->validate([
            'status' => ['nullable', 'string', 'in:todo,in_progress,done,blocked,cancelled'],
            'priority' => ['nullable', 'string', 'in:low,medium,high,urgent'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'search' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $perPage = (int) $request->get('per_page', 15);
        $filters = $request->only(['status', 'priority', 'assigned_to', 'search']);
        $tasks = $this->schoolTaskService->listBySchool($school, $filters, $perPage);

        return $this->paginatedResponse($tasks, 'Tâches récupérées.');
    }

    public function show(int $school, int $task): JsonResponse
    {
        $record = $this->schoolTaskService->getBySchool($school, $task);

        if (!$record) {
            return $this->errorResponse('Tâche introuvable.', [], 404);
        }

        return $this->successResponse($record, 'Tâche récupérée.');
    }

    public function store(SchoolTaskRequest $request, int $school): JsonResponse
    {
        $data = $request->validated();
        $actor = $request->user();

        if (!$actor) {
            return $this->errorResponse('Utilisateur non authentifié.', [], 401);
        }

        if (!empty($data['assigned_to']) && !$this->isUserSchoolMember($school, (int) $data['assigned_to'])) {
            return $this->errorResponse('L\'utilisateur assigné n\'appartient pas à cette école.', [], 422);
        }

        $data['school_id'] = $school;
        $data['created_by'] = $actor->id;

        if (($data['status'] ?? 'todo') === 'done') {
            $data['completed_at'] = now();
        }

        $task = $this->schoolTaskService->create($data);

        return $this->successResponse($task->load(['assignee:id,name,email', 'creator:id,name,email']), 'Tâche créée.', 201);
    }

    public function update(SchoolTaskRequest $request, int $school, int $task): JsonResponse
    {
        $record = $this->schoolTaskService->getBySchool($school, $task);
        if (!$record) {
            return $this->errorResponse('Tâche introuvable.', [], 404);
        }

        $actor = $request->user();
        if (!$actor) {
            return $this->errorResponse('Utilisateur non authentifié.', [], 401);
        }

        $isDirector = (int) School::query()->whereKey($school)->value('director_id') === (int) $actor->id;

        $data = $request->validated();

        if (!$isDirector && (int) $record->assigned_to !== (int) $actor->id) {
            return $this->errorResponse('Vous ne pouvez modifier que vos propres tâches.', [], 403);
        }

        if (!$isDirector) {
            $data = array_intersect_key($data, array_flip(['status']));
        }

        if (array_key_exists('assigned_to', $data) && !empty($data['assigned_to']) && !$this->isUserSchoolMember($school, (int) $data['assigned_to'])) {
            return $this->errorResponse('L\'utilisateur assigné n\'appartient pas à cette école.', [], 422);
        }

        if (array_key_exists('status', $data)) {
            if ($data['status'] === 'done') {
                $data['completed_at'] = now();
            } elseif ($record->completed_at !== null) {
                $data['completed_at'] = null;
            }
        }

        $updated = $this->schoolTaskService->update($record->id, $data);

        return $this->successResponse($updated->load(['assignee:id,name,email', 'creator:id,name,email']), 'Tâche mise à jour.');
    }

    public function destroy(Request $request, int $school, int $task): JsonResponse
    {
        $record = $this->schoolTaskService->getBySchool($school, $task);
        if (!$record) {
            return $this->errorResponse('Tâche introuvable.', [], 404);
        }

        $actor = $request->user();
        if (!$actor) {
            return $this->errorResponse('Utilisateur non authentifié.', [], 401);
        }

        $isDirector = (int) School::query()->whereKey($school)->value('director_id') === (int) $actor->id;
        if (!$isDirector) {
            return $this->errorResponse('Seul le gestionnaire de l\'école peut supprimer une tâche.', [], 403);
        }

        $this->schoolTaskService->delete($record->id);

        return $this->successResponse(null, 'Tâche supprimée.');
    }

    private function isUserSchoolMember(int $schoolId, int $userId): bool
    {
        $school = School::query()->find($schoolId);
        if (!$school) {
            return false;
        }

        if ((int) $school->director_id === $userId) {
            return true;
        }

        $isTeacher = Teacher::query()
            ->where('school_id', $schoolId)
            ->where('user_id', $userId)
            ->exists();

        if ($isTeacher) {
            return true;
        }

        return Student::query()
            ->where('school_id', $schoolId)
            ->where('user_id', $userId)
            ->exists();
    }
}
