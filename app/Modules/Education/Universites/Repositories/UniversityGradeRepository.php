<?php

namespace App\Modules\Education\Universites\Repositories;

use App\Modules\Education\Universites\Models\UniversityGrade;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class UniversityGradeRepository extends BaseRepository
{
    public function __construct(UniversityGrade $model)
    {
        parent::__construct($model);
    }

    public function getByStudent(int $studentId, ?string $semester = null): Collection
    {
        $query = $this->model->where('student_id', $studentId)->with(['course']);

        if ($semester) {
            $query->where('semester', $semester);
        }

        return $query->get();
    }

    public function upsert(array $data): UniversityGrade
    {
        return $this->model->updateOrCreate(
            [
                'student_id' => $data['student_id'],
                'course_id'  => $data['course_id'],
                'semester'   => $data['semester'],
            ],
            $data
        );
    }
}
