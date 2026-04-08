<?php

namespace App\Modules\Education\Universites\Repositories;

use App\Modules\Education\Universites\Models\Course;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class CourseRepository extends BaseRepository
{
    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    public function getByDepartment(int $departmentId): Collection
    {
        return $this->model->where('department_id', $departmentId)->get();
    }
}
