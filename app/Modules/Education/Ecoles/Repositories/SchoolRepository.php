<?php

namespace App\Modules\Education\Ecoles\Repositories;

use App\Models\User;
use App\Modules\Education\Ecoles\Models\School;
use App\Modules\Education\Ecoles\Models\Student;
use App\Modules\Education\Ecoles\Models\Teacher;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class SchoolRepository extends BaseRepository
{
    public function __construct(School $model)
    {
        parent::__construct($model);
    }

    public function paginateWithFilters(array $filters = [], int $perPage = 15, ?User $user = null): LengthAwarePaginator
    {
        $query = $this->model->query()->withCount(['students', 'teachers']);

        // Policy:
        // - super_admin/admin can list all schools
        // - other users can list only their assigned schools
        if (!$user) {
            $query->whereRaw('1 = 0');
        } elseif (!$user->hasAnyRole(['super_admin', 'admin'])) {
            $schoolIds = [];

            $directorSchoolId = School::query()
                ->where('director_id', $user->id)
                ->value('id');
            if ($directorSchoolId) {
                $schoolIds[] = (int) $directorSchoolId;
            }

            $teacherSchoolId = Teacher::query()
                ->where('user_id', $user->id)
                ->value('school_id');
            if ($teacherSchoolId) {
                $schoolIds[] = (int) $teacherSchoolId;
            }

            $studentSchoolId = Student::query()
                ->where('user_id', $user->id)
                ->value('school_id');
            if ($studentSchoolId) {
                $schoolIds[] = (int) $studentSchoolId;
            }

            $schoolIds = array_values(array_unique($schoolIds));

            if (empty($schoolIds)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('id', $schoolIds);
            }
        }

        if (!empty($filters['level'])) {
            $query->whereJsonContains('level_types', $filters['level']);
        }

        if (!empty($filters['education_submodule'])) {
            $query->where('education_submodule', $filters['education_submodule']);
        }

        if (!empty($filters['subscription_status'])) {
            $query->where('subscription_status', $filters['subscription_status']);
        }

        if (!empty($filters['city'])) {
            $query->where('city', 'like', '%' . $filters['city'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        return $query->latest()->paginate($perPage);
    }
}
