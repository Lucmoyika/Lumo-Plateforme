<?php

namespace App\Modules\Education\Ecoles\Services;

use App\Modules\Education\Ecoles\Models\SchoolTask;
use App\Modules\Education\Ecoles\Repositories\SchoolTaskRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class SchoolTaskService extends BaseService
{
    public function __construct(protected SchoolTaskRepository $schoolTaskRepository)
    {
        parent::__construct($schoolTaskRepository);
    }

    public function listBySchool(int $schoolId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->schoolTaskRepository->paginateBySchool($schoolId, $filters, $perPage);
    }

    public function getBySchool(int $schoolId, int $taskId): ?SchoolTask
    {
        return $this->schoolTaskRepository->findBySchool($schoolId, $taskId);
    }
}
