<?php

namespace App\Modules\Education\Universites\Services;

use App\Modules\Education\Universites\Repositories\UniversityRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class UniversityService extends BaseService
{
    public function __construct(protected UniversityRepository $universityRepository)
    {
        parent::__construct($universityRepository);
    }

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->universityRepository->paginateWithFilters($filters, $perPage);
    }
}
