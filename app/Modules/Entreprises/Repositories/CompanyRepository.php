<?php

namespace App\Modules\Entreprises\Repositories;

use App\Modules\Entreprises\Models\Company;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyRepository extends BaseRepository
{
    public function __construct(Company $model)
    {
        parent::__construct($model);
    }

    public function paginateWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (!empty($filters['sector'])) {
            $query->where('sector', $filters['sector']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['verified'])) {
            $query->where('is_verified', filter_var($filters['verified'], FILTER_VALIDATE_BOOLEAN));
        }

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        return $query->latest()->paginate($perPage);
    }
}
