<?php

namespace App\Modules\Education\Universites\Repositories;

use App\Modules\Education\Universites\Models\University;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class UniversityRepository extends BaseRepository
{
    public function __construct(University $model)
    {
        parent::__construct($model);
    }

    public function paginateWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (!empty($filters['city'])) {
            $query->where('city', 'like', '%' . $filters['city'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('acronym', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->latest()->paginate($perPage);
    }
}
