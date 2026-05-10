<?php

namespace App\Modules\Education\Ecoles\Services;

use App\Modules\Education\Ecoles\Repositories\SchoolClassRepository;
use App\Modules\Education\Ecoles\Models\School;
use App\Services\BaseService;
use Illuminate\Validation\ValidationException;

class SchoolClassService extends BaseService
{
    // Niveaux autorisés par sous-module/type d'école
    private const MATERNELLE_LEVELS = ['1er', '2e', '3e'];
    private const PRIMAIRE_LEVELS = ['1er', '2e', '3e', '4e', '5e', '6e'];
    private const SECONDAIRE_LEVELS = ['1ère', '2e', '3e', '4e'];
    private const HUMANITES_LEVELS = ['5e', '6e'];

    public function __construct(protected SchoolClassRepository $classRepository)
    {
        parent::__construct($classRepository);
    }

    /**
     * Créer une classe avec validation des niveaux
     */
    public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        $this->validateClassLevelForSchool($data);
        $this->normalizeEducationSubmodule($data);

        return parent::create($data);
    }

    /**
     * Mettre à jour une classe avec validation
     */
    public function update(int $id, array $data): \Illuminate\Database\Eloquent\Model
    {
        $class = $this->repository->getById($id);

        if ($class && isset($data['level'])) {
            $data['school_id'] = $class->school_id;
            $this->validateClassLevelForSchool($data);
        }

        return parent::update($id, $data);
    }

    /**
     * Valider que le niveau appartient aux niveaux autorisés de l'école
     */
    protected function validateClassLevelForSchool(array $data): void
    {
        if (!isset($data['school_id']) || !isset($data['level'])) {
            return;
        }

        $schoolId = $data['school_id'];
        $level = $data['level'];

        $school = School::findOrFail($schoolId);
        $levelTypes = $school->level_types ?? [];

        $allowedLevels = [];

        // Déterminer les niveaux autorisés selon les level_types de l'école
        if (in_array('maternelle', $levelTypes)) {
            $allowedLevels = array_merge($allowedLevels, self::MATERNELLE_LEVELS);
        }
        if (in_array('primaire', $levelTypes)) {
            $allowedLevels = array_merge($allowedLevels, self::PRIMAIRE_LEVELS);
        }
        if (in_array('secondaire', $levelTypes)) {
            $allowedLevels = array_merge($allowedLevels, self::SECONDAIRE_LEVELS);
        }
        if (in_array('humanites', $levelTypes)) {
            $allowedLevels = array_merge($allowedLevels, self::HUMANITES_LEVELS);
        }

        if (empty($allowedLevels)) {
            throw ValidationException::withMessages([
                'level' => 'Aucun niveau configuré pour cette école.',
            ]);
        }

        if (!in_array($level, array_unique($allowedLevels))) {
            $allowedStr = implode(', ', array_unique($allowedLevels));
            throw ValidationException::withMessages([
                'level' => "Le niveau '{$level}' n'est pas autorisé. Niveaux autorisés: {$allowedStr}",
            ]);
        }
    }

    /**
     * Normaliser et stocker le submodule dans la classe
     * pour denormalisation/recherche rapide
     */
    protected function normalizeEducationSubmodule(array &$data): void
    {
        if (!isset($data['school_id'])) {
            return;
        }

        $school = School::find($data['school_id']);
        if (!$school || !$school->education_submodule) {
            return;
        }

        $data['education_submodule'] = $school->education_submodule;
    }

    /**
     * Obtenir les classes par école avec filtrage optionnel
     */
    public function getBySchool(
        int $schoolId,
        ?string $academicYear = null,
        bool $includeArchived = false,
        ?string $level = null
    ): \Illuminate\Database\Eloquent\Collection {
        return $this->classRepository->getBySchool(
            $schoolId,
            $academicYear,
            $includeArchived,
            $level
        );
    }

    /**
     * Obtenir les classes d'un enseignant pour l'année scolaire
     */
    public function getByTeacher(int $teacherId, ?string $academicYear = null): \Illuminate\Database\Eloquent\Collection
    {
        return $this->classRepository->query()
            ->where('teacher_id', $teacherId)
            ->when($academicYear, function ($q) use ($academicYear) {
                $q->where('academic_year', $academicYear);
            })
            ->whereNull('archived_at')
            ->get();
    }

    /**
     * Obtenir les niveaux autorisés pour une école
     */
    public function getAllowedLevelsForSchool(int $schoolId): array
    {
        $school = School::find($schoolId);
        if (!$school || !$school->level_types) {
            return [];
        }

        $allowed = [];

        if (in_array('maternelle', $school->level_types)) {
            $allowed = array_merge($allowed, self::MATERNELLE_LEVELS);
        }
        if (in_array('primaire', $school->level_types)) {
            $allowed = array_merge($allowed, self::PRIMAIRE_LEVELS);
        }
        if (in_array('secondaire', $school->level_types)) {
            $allowed = array_merge($allowed, self::SECONDAIRE_LEVELS);
        }
        if (in_array('humanites', $school->level_types)) {
            $allowed = array_merge($allowed, self::HUMANITES_LEVELS);
        }

        return array_unique($allowed);
    }
}

