<?php

namespace App\Modules\Education\Ecoles\SubModules\Secondaire\Repositories;

use App\Modules\Education\Ecoles\SubModules\Secondaire\Models\SecondaireTeacher;
use App\Repositories\BaseRepository;

class SecondaireTeacherRepository extends BaseRepository
{
    public function __construct(SecondaireTeacher $model)
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
