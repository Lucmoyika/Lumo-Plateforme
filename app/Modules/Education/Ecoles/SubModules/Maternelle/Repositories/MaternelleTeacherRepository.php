<?php

namespace App\Modules\Education\Ecoles\SubModules\Maternelle\Repositories;

use App\Modules\Education\Ecoles\SubModules\Maternelle\Models\MaternelleTeacher;
use App\Repositories\BaseRepository;

class MaternelleTeacherRepository extends BaseRepository
{
    public function __construct(MaternelleTeacher $model)
    {
        parent::__construct($model);
    }

    public function getBySchool(int $schoolId, bool $includeArchived = false): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->model
            ->where('school_id', $schoolId)
            ->where('gender', 'F');

        if (!$includeArchived) {
            $query->whereNull('archived_at');
        }

        return $query->with('user')->get();
    }

    public function getMainTeachers(int $schoolId, bool $includeArchived = false): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->model
            ->where('school_id', $schoolId)
            ->where('role', 'teacher')
            ->where('gender', 'F');

        if (!$includeArchived) {
            $query->whereNull('archived_at');
        }

        return $query->with('user')->get();
    }

    public function getAssistants(int $schoolId, bool $includeArchived = false): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->model
            ->where('school_id', $schoolId)
            ->where('role', 'assistant')
            ->where('gender', 'F');

        if (!$includeArchived) {
            $query->whereNull('archived_at');
        }

        return $query->with('user')->get();
    }

    public function paginate(int $schoolId, int $perPage = 15, bool $includeArchived = false): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = $this->model
            ->where('school_id', $schoolId)
            ->where('gender', 'F')
            ->with('user');

        if (!$includeArchived) {
            $query->whereNull('archived_at');
        }

        return $query->paginate($perPage);
    }
}
