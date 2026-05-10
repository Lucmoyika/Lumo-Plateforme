<?php

namespace App\Modules\Education\Ecoles\Repositories;

use App\Modules\Education\Ecoles\Models\SchoolTask;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class SchoolTaskRepository extends BaseRepository
{
    public function __construct(SchoolTask $model)
    {
        parent::__construct($model);
    }

    public function paginateBySchool(int $schoolId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model
            ->where('school_id', $schoolId)
            ->with(['assignee:id,name,email', 'creator:id,name,email'])
            ->latest();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', (int) $filters['assigned_to']);
        }

        if (!empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function findBySchool(int $schoolId, int $taskId): ?SchoolTask
    {
        return $this->model
            ->with(['assignee:id,name,email', 'creator:id,name,email'])
            ->where('school_id', $schoolId)
            ->find($taskId);
    }
}
