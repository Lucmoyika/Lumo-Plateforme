<?php

namespace App\Modules\Education\Ecoles\Services;

use App\Modules\Education\Ecoles\Repositories\SchoolRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class SchoolService extends BaseService
{
    public function __construct(protected SchoolRepository $schoolRepository)
    {
        parent::__construct($schoolRepository);
    }

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->schoolRepository->paginateWithFilters($filters, $perPage);
    }
}
