<?php

namespace App\Modules\Education\Universites\Repositories;

use App\Modules\Education\Universites\Models\Faculty;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class FacultyRepository extends BaseRepository
{
    public function __construct(Faculty $model)
    {
        parent::__construct($model);
    }

    public function getByUniversity(int $universityId): Collection
    {
        return $this->model->where('university_id', $universityId)->with(['departments'])->get();
    }
}
