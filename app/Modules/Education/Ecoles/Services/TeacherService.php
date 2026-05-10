<?php

namespace App\Modules\Education\Ecoles\Services;

use App\Modules\Education\Ecoles\Repositories\TeacherRepository;
use App\Modules\Education\Ecoles\Models\School;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class TeacherService extends BaseService
{
    public function __construct(protected TeacherRepository $teacherRepository)
    {
        parent::__construct($teacherRepository);
    }

    /**
     * Créer un enseignant avec validation du sous-module
     */
    public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Valider les règles de genre selon le sous-module
        $this->validateTeacherGenderForSchool($data);

        return parent::create($data);
    }

    /**
     * Mettre à jour un enseignant avec validation du sous-module
     */
    public function update(int $id, array $data): \Illuminate\Database\Eloquent\Model
    {
        $teacher = $this->repository->getById($id);

        if ($teacher && isset($data['gender'])) {
            // Recharger school data
            $data['school_id'] = $teacher->school_id;
            $this->validateTeacherGenderForSchool($data);
        }

        return parent::update($id, $data);
    }

    /**
     * Valider le genre de l'enseignant selon le sous-module de l'école
     * - Maternelle: Femmes uniquement (F)
     * - Primaire: Mixte (M/F)
     */
    protected function validateTeacherGenderForSchool(array $data): void
    {
        if (!isset($data['school_id']) || !isset($data['gender'])) {
            return;
        }

        $schoolId = $data['school_id'];
        $school = School::with(['classes' => function ($q) {
            $q->select('education_submodule')->limit(1);
        }])->findOrFail($schoolId);

        // Déterminer le sous-module
        $submodule = null;
        if (in_array('maternelle', $school->level_types)) {
            $submodule = 'maternelle';
        } elseif (in_array('primaire', $school->level_types)) {
            $submodule = 'primaire';
        }

        // Appliquer les règles
        if ($submodule === 'maternelle' && $data['gender'] !== 'F') {
            throw ValidationException::withMessages([
                'gender' => 'La Maternelle accepte uniquement les enseignantes (F).',
            ]);
        }

        // Primaire accepte M et F, pas de validation supplémentaire
    }

    /**
     * Lister les enseignants par école avec filtrage optionnel par genre
     */
    public function listBySchool(
        int $schoolId,
        int $perPage = 15,
        ?string $academicYear = null,
        bool $includeArchived = false,
        ?string $gender = null
    ): LengthAwarePaginator {
        return $this->teacherRepository->paginateBySchool(
            $schoolId,
            $perPage,
            $academicYear,
            $includeArchived,
            $gender
        );
    }

    /**
     * Obtenir les enseignants principaux (role='teacher')
     */
    public function getMainTeachers(int $schoolId, bool $includeArchived = false): Collection
    {
        $query = $this->repository->query()
            ->where('school_id', $schoolId)
            ->where('role', 'teacher');

        if (!$includeArchived) {
            $query->whereNull('archived_at');
        }

        return $query->get();
    }

    /**
     * Obtenir les assistants (role='assistant')
     */
    public function getAssistants(int $schoolId, bool $includeArchived = false): Collection
    {
        $query = $this->repository->query()
            ->where('school_id', $schoolId)
            ->where('role', 'assistant');

        if (!$includeArchived) {
            $query->whereNull('archived_at');
        }

        return $query->get();
    }

    /**
     * Obtenir les remplaçants (role='substitute')
     */
    public function getSubstitutes(int $schoolId, bool $includeArchived = false): Collection
    {
        $query = $this->repository->query()
            ->where('school_id', $schoolId)
            ->where('role', 'substitute');

        if (!$includeArchived) {
            $query->whereNull('archived_at');
        }

        return $query->get();
    }

    public function getSchedule(int $teacherId): Collection
    {
        return $this->teacherRepository->getSchedule($teacherId);
    }

    public function getClasses(int $teacherId): Collection
    {
        return $this->teacherRepository->getClasses($teacherId);
    }
}

