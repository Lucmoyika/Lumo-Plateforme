<?php

namespace App\Modules\Education\Ecoles\SubModules\Humanites\Repositories;

use App\Modules\Education\Ecoles\SubModules\Humanites\Models\HumanitesClass;
use App\Repositories\BaseRepository;

class HumanitesClassRepository extends BaseRepository
{
    public function __construct(HumanitesClass $model)
    {
        parent::__construct($model);
    }

    public function getBySchool(int $schoolId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model
            ->where('school_id', $schoolId)
            ->whereIn('level', ['5e', '6e'])
            ->whereNull('archived_at')
            ->orderBy('level')
            ->orderBy('class_variant')
            ->get();
    }
}
