<?php

namespace App\Modules\Education\Ecoles\SubModules\Primaire\Repositories;

use App\Modules\Education\Ecoles\SubModules\Primaire\Models\PrimaireClass;
use App\Repositories\BaseRepository;

class PrimaireClassRepository extends BaseRepository
{
    public function __construct(PrimaireClass $model)
    {
        parent::__construct($model);
    }

    public function getBySchool(int $schoolId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model
            ->where('school_id', $schoolId)
            ->whereIn('level', ['1er', '2e', '3e', '4e', '5e', '6e'])
            ->whereNull('archived_at')
            ->orderBy('level')
            ->orderBy('class_variant')
            ->get();
    }
}
