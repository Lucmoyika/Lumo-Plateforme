<?php

namespace App\Modules\Education\Ecoles\SubModules\Secondaire\Services;

use App\Services\BaseService;

class SecondaireTeacherService extends BaseService
{
    public function __construct(private readonly \App\Modules\Education\Ecoles\SubModules\Secondaire\Repositories\SecondaireTeacherRepository $teacherRepository)
    {
        parent::__construct($teacherRepository);
    }

    public function getAll(int $schoolId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->teacherRepository->getBySchool($schoolId);
    }
}
