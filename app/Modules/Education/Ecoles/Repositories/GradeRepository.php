<?php

namespace App\Modules\Education\Ecoles\Repositories;

use App\Modules\Education\Ecoles\Models\Grade;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class GradeRepository extends BaseRepository
{
    public function __construct(Grade $model)
    {
        parent::__construct($model);
    }

    public function getByStudent(int $studentId, ?string $period = null): Collection
    {
        $query = $this->model->where('student_id', $studentId);

        if ($period) {
            $query->where('period', $period);
        }

        return $query->orderBy('subject')->get();
    }

    public function getByClass(int $classId, ?string $period = null): Collection
    {
        $query = $this->model->where('class_id', $classId);

        if ($period) {
            $query->where('period', $period);
        }

        return $query->with(['student.user'])->get();
    }

    public function upsert(array $data): Grade
    {
        return $this->model->updateOrCreate(
            [
                'student_id' => $data['student_id'],
                'subject'    => $data['subject'],
                'period'     => $data['period'],
            ],
            $data
        );
    }
}
