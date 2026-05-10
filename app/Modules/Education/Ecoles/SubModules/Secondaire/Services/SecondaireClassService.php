<?php

namespace App\Modules\Education\Ecoles\SubModules\Secondaire\Services;

use App\Services\BaseService;

class SecondaireClassService extends BaseService
{
    private const SECONDAIRE_LEVELS = ['1ère', '2e', '3e', '4e'];

    public function __construct(private readonly \App\Modules\Education\Ecoles\SubModules\Secondaire\Repositories\SecondaireClassRepository $classRepository)
    {
        parent::__construct($classRepository);
    }

    public function getBySchool(int $schoolId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->classRepository->getBySchool($schoolId);
    }
}
