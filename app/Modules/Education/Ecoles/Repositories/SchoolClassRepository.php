<?php

namespace App\Modules\Education\Ecoles\Repositories;

use App\Modules\Education\Ecoles\Models\SchoolClass;
use App\Repositories\BaseRepository;

class SchoolClassRepository extends BaseRepository
{
    public function __construct(SchoolClass $model)
    {
        parent::__construct($model);
    }

    /**
     * Obtenir les classes d'une école avec filtres optionnels
     */
    public function getBySchool(
        int $schoolId,
        ?string $academicYear = null,
        bool $includeArchived = false,
        ?string $level = null
    ): \Illuminate\Database\Eloquent\Collection {
        $query = $this->model
            ->where('school_id', $schoolId)
            ->with(['teacher']);

        if ($academicYear) {
            $query->where('academic_year', $academicYear);
        }

        if ($level) {
            $query->where('level', $level);
        }

        if (!$includeArchived) {
            $query->whereNull('archived_at');
        }

        return $query->orderBy('level')->orderBy('class_variant')->get();
    }

    /**
     * Obtenir les classes d'un niveau donné
     */
    public function getByLevel(int $schoolId, string $level, ?string $academicYear = null): \Illuminate\Database\Eloquent\Collection
    {
        return $this->getBySchool($schoolId, $academicYear, false, $level);
    }

    /**
     * Obtenir le nombre de classes par niveau
     */
    public function countClassesByLevel(int $schoolId, ?string $academicYear = null): array
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

    /**
     * Vérifier si une classe existe avec le même niveau et variante
     */
    public function classExists(int $schoolId, string $level, ?string $variant = null, ?string $academicYear = null): bool
    {
        $query = $this->model
            ->where('school_id', $schoolId)
            ->where('level', $level)
            ->whereNull('archived_at');

        if ($variant) {
            $query->where('class_variant', $variant);
        } else {
            $query->whereNull('class_variant');
        }

        if ($academicYear) {
            $query->where('academic_year', $academicYear);
        }

        return $query->exists();
    }
}
