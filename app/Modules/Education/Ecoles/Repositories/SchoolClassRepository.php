<?php

namespace App\Modules\Education\Ecoles\Repositories;

use App\Modules\Education\Ecoles\Models\SchoolClass;
use App\Repositories\BaseRepository;

class SchoolClassRepository extends BaseRepository
{
    public function __construct(SchoolClass $model)
    {
        parent::__construct($model);
    }

    public function getBySchool(int $schoolId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('school_id', $schoolId)->with(['teacher'])->get();
    }
}
