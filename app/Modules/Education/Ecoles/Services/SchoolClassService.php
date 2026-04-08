<?php

namespace App\Modules\Education\Ecoles\Services;

use App\Modules\Education\Ecoles\Repositories\SchoolClassRepository;
use App\Services\BaseService;

class SchoolClassService extends BaseService
{
    public function __construct(protected SchoolClassRepository $classRepository)
    {
        parent::__construct($classRepository);
    }

    public function getBySchool(int $schoolId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->classRepository->getBySchool($schoolId);
    }
}
