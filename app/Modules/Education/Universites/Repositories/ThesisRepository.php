<?php

namespace App\Modules\Education\Universites\Repositories;

use App\Modules\Education\Universites\Models\Thesis;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ThesisRepository extends BaseRepository
{
    public function __construct(Thesis $model)
    {
        parent::__construct($model);
    }

    public function paginateWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['student_id'])) {
            $query->where('student_id', $filters['student_id']);
        }

        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        return $query->latest()->paginate($perPage);
    }
}
