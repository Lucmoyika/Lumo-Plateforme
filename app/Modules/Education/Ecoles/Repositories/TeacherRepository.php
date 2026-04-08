<?php

namespace App\Modules\Education\Ecoles\Repositories;

use App\Modules\Education\Ecoles\Models\Teacher;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class TeacherRepository extends BaseRepository
{
    public function __construct(Teacher $model)
    {
        parent::__construct($model);
    }

    public function paginateBySchool(int $schoolId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('school_id', $schoolId)->with(['user'])->paginate($perPage);
    }

    public function getSchedule(int $teacherId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->findOrFail($teacherId)->schedules()->get();
    }

    public function getClasses(int $teacherId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->findOrFail($teacherId)->classes()->get();
    }
}
