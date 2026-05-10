<?php

namespace App\Modules\Education\Ecoles\SubModules\Humanites\Repositories;

use App\Modules\Education\Ecoles\SubModules\Humanites\Models\HumanitesTeacher;
use App\Repositories\BaseRepository;

class HumanitesTeacherRepository extends BaseRepository
{
    public function __construct(HumanitesTeacher $model)
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
