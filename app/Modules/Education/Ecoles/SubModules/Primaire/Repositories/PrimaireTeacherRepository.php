<?php

namespace App\Modules\Education\Ecoles\SubModules\Primaire\Repositories;

use App\Modules\Education\Ecoles\SubModules\Primaire\Models\PrimaireTeacher;
use App\Repositories\BaseRepository;

class PrimaireTeacherRepository extends BaseRepository
{
    public function __construct(PrimaireTeacher $model)
    {
        parent::__construct($model);
    }

    public function getBySchool(int $schoolId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model
            ->where('school_id', $schoolId)
            ->whereNull('archived_at')
            ->with('user')
            ->get();
    }
}
