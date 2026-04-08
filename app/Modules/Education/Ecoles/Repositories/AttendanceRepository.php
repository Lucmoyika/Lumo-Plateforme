<?php

namespace App\Modules\Education\Ecoles\Repositories;

use App\Modules\Education\Ecoles\Models\Attendance;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class AttendanceRepository extends BaseRepository
{
    public function __construct(Attendance $model)
    {
        parent::__construct($model);
    }

    public function getByClass(int $classId, ?string $date = null): Collection
    {
        $query = $this->model->where('class_id', $classId)->with(['student.user']);

        if ($date) {
            $query->whereDate('date', $date);
        }

        return $query->get();
    }

    public function getByStudent(int $studentId, ?string $from = null, ?string $to = null): Collection
    {
        $query = $this->model->where('student_id', $studentId);

        if ($from) {
            $query->whereDate('date', '>=', $from);
        }

        if ($to) {
            $query->whereDate('date', '<=', $to);
        }

        return $query->orderBy('date')->get();
    }

    public function upsertRecord(array $data): Attendance
    {
        return $this->model->updateOrCreate(
            [
                'student_id' => $data['student_id'],
                'class_id'   => $data['class_id'],
                'date'       => $data['date'],
            ],
            $data
        );
    }
}
