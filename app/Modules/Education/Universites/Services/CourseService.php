<?php

namespace App\Modules\Education\Universites\Services;

use App\Modules\Education\Universites\Repositories\CourseRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class CourseService extends BaseService
{
    public function __construct(protected CourseRepository $courseRepository)
    {
        parent::__construct($courseRepository);
    }

    public function getByDepartment(int $departmentId): Collection
    {
        return $this->courseRepository->getByDepartment($departmentId);
    }
}
