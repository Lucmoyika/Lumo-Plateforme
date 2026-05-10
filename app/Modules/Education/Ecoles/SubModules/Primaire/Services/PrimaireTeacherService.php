<?php

namespace App\Modules\Education\Ecoles\SubModules\Primaire\Services;

use App\Services\BaseService;

class PrimaireTeacherService extends BaseService
{
    public function __construct(private readonly \App\Modules\Education\Ecoles\SubModules\Primaire\Repositories\PrimaireTeacherRepository $teacherRepository)
    {
        parent::__construct($teacherRepository);
    }

    public function getAll(int $schoolId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->teacherRepository->getBySchool($schoolId);
    }
}
