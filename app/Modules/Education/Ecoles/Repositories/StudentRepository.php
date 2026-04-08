<?php

namespace App\Modules\Education\Ecoles\Repositories;

use App\Modules\Education\Ecoles\Models\Student;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class StudentRepository extends BaseRepository
{
    public function __construct(Student $model)
    {
        parent::__construct($model);
    }

    public function paginateBySchool(int $schoolId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('school_id', $schoolId)->with(['user', 'class'])->paginate($perPage);
    }

    public function getGrades(int $studentId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->findOrFail($studentId)->grades()->with(['subject'])->get();
    }
}
