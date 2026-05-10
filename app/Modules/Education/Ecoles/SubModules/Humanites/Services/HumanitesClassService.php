<?php

namespace App\Modules\Education\Ecoles\SubModules\Humanites\Services;

use App\Services\BaseService;

class HumanitesClassService extends BaseService
{
    private const HUMANITES_LEVELS = ['5e', '6e'];

    public function __construct(private readonly \App\Modules\Education\Ecoles\SubModules\Humanites\Repositories\HumanitesClassRepository $classRepository)
    {
        parent::__construct($classRepository);
    }

    public function getBySchool(int $schoolId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->classRepository->getBySchool($schoolId);
    }
}
