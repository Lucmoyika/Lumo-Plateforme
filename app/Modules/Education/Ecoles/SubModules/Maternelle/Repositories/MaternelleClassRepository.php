<?php

namespace App\Modules\Education\Ecoles\SubModules\Maternelle\Repositories;

use App\Modules\Education\Ecoles\SubModules\Maternelle\Models\MaternelleClass;
use App\Repositories\BaseRepository;

class MaternelleClassRepository extends BaseRepository
{
    public function __construct(MaternelleClass $model)
    {
        parent::__construct($model);
    }

    public function getBySchool(int $schoolId, ?string $academicYear = null, bool $includeArchived = false): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->model
            ->where('school_id', $schoolId)
            ->whereIn('level', ['1er', '2e', '3e']);

        if ($academicYear) {
            $query->where('academic_year', $academicYear);
        }

        if (!$includeArchived) {
            $query->whereNull('archived_at');
        }

        return $query->orderBy('level')->get();
    }

    public function getByLevel(int $schoolId, string $level, ?string $academicYear = null): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model
            ->where('school_id', $schoolId)
            ->where('level', $level)
            ->whereNull('archived_at')
            ->when($academicYear, function ($q) use ($academicYear) {
                $q->where('academic_year', $academicYear);
            })
            ->get();
    }

    public function countByLevel(int $schoolId, ?string $academicYear = null): array
    {
        $query = $this->model
            ->where('school_id', $schoolId)
            ->whereNull('archived_at');

        if ($academicYear) {
            $query->where('academic_year', $academicYear);
        }

        return $query
            ->groupBy('level')
            ->selectRaw('level, count(*) as count')
            ->pluck('count', 'level')
            ->toArray();
    }
}
