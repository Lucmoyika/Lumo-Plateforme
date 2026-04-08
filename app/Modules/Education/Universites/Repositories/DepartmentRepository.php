<?php

namespace App\Modules\Education\Universites\Repositories;

use App\Modules\Education\Universites\Models\Department;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class DepartmentRepository extends BaseRepository
{
    public function __construct(Department $model)
    {
        parent::__construct($model);
    }

    public function getByFaculty(int $facultyId): Collection
    {
        return $this->model->where('faculty_id', $facultyId)->with(['courses'])->get();
    }
}
