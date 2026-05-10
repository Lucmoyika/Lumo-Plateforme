<?php

namespace App\Modules\Education\Ecoles\SubModules\Humanites\Services;

use App\Services\BaseService;

class HumanitesTeacherService extends BaseService
{
    public function __construct(private readonly \App\Modules\Education\Ecoles\SubModules\Humanites\Repositories\HumanitesTeacherRepository $teacherRepository)
    {
        parent::__construct($teacherRepository);
    }

    public function getAll(int $schoolId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->teacherRepository->getBySchool($schoolId);
    }
}
