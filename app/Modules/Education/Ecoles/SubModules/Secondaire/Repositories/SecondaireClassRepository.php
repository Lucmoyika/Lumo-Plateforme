<?php

namespace App\Modules\Education\Ecoles\SubModules\Secondaire\Repositories;

use App\Modules\Education\Ecoles\SubModules\Secondaire\Models\SecondaireClass;
use App\Repositories\BaseRepository;

class SecondaireClassRepository extends BaseRepository
{
    public function __construct(SecondaireClass $model)
    {
        parent::__construct($model);
    }

    public function getBySchool(int $schoolId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model
            ->where('school_id', $schoolId)
            ->whereIn('level', ['1ère', '2e', '3e', '4e'])
            ->whereNull('archived_at')
            ->orderBy('level')
            ->orderBy('class_variant')
            ->get();
    }
}
