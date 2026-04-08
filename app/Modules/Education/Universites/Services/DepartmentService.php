<?php

namespace App\Modules\Education\Universites\Services;

use App\Modules\Education\Universites\Repositories\DepartmentRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class DepartmentService extends BaseService
{
    public function __construct(protected DepartmentRepository $departmentRepository)
    {
        parent::__construct($departmentRepository);
    }

    public function getByFaculty(int $facultyId): Collection
    {
        return $this->departmentRepository->getByFaculty($facultyId);
    }
}
