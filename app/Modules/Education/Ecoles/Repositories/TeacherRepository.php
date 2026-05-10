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

    /**
     * Paginer les enseignants d'une école avec filtres optionnels
     */
    public function paginateBySchool(
        int $schoolId,
        int $perPage = 15,
        ?string $academicYear = null,
        bool $includeArchived = false,
        ?string $gender = null,
        ?string $role = null
    ): LengthAwarePaginator {
        $query = $this->model
            ->where('school_id', $schoolId)
            ->with(['user']);

        if ($academicYear) {
            $query->whereHas('classes', function ($q) use ($academicYear) {
                $q->where('academic_year', $academicYear);
            });
        }

        if ($gender) {
            $query->where('gender', $gender);
        }

        if ($role) {
            $query->where('role', $role);
        }

        if (!$includeArchived) {
            $query->whereNull('archived_at');
        }

        return $query->paginate($perPage);
    }

    /**
     * Obtenir les enseignants d'une école filtrés par genre
     */
    public function getBySchoolAndGender(int $schoolId, string $gender, bool $includeArchived = false): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->model
            ->where('school_id', $schoolId)
            ->where('gender', $gender);

        if (!$includeArchived) {
            $query->whereNull('archived_at');
        }

        return $query->get();
    }

    /**
     * Obtenir les enseignants d'une école filtrés par rôle
     */
    public function getBySchoolAndRole(int $schoolId, string $role, bool $includeArchived = false): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->model
            ->where('school_id', $schoolId)
            ->where('role', $role);

        if (!$includeArchived) {
            $query->whereNull('archived_at');
        }

        return $query->get();
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
