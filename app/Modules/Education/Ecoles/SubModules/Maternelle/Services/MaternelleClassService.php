<?php

namespace App\Modules\Education\Ecoles\SubModules\Maternelle\Services;

use App\Modules\Education\Ecoles\SubModules\Maternelle\Repositories\MaternelleClassRepository;
use App\Services\BaseService;
use Illuminate\Validation\ValidationException;

class MaternelleClassService extends BaseService
{
    private const MATERNELLE_LEVELS = ['1er', '2e', '3e'];

    public function __construct(protected MaternelleClassRepository $classRepository)
    {
        parent::__construct($classRepository);
    }

    public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        $this->validateMaternelleLevel($data);
        return parent::create($data);
    }

    public function update(int $id, array $data): \Illuminate\Database\Eloquent\Model
    {
        $class = $this->repository->getById($id);

        if ($class && isset($data['level'])) {
            $this->validateMaternelleLevel($data);
        }

        return parent::update($id, $data);
    }

    protected function validateMaternelleLevel(array $data): void
    {
        if (!isset($data['level'])) {
            return;
        }

        $level = $data['level'];

        if (!in_array($level, self::MATERNELLE_LEVELS)) {
            $allowed = implode(', ', self::MATERNELLE_LEVELS);
            throw ValidationException::withMessages([
                'level' => "Maternelle: niveaux autorisés = {$allowed}. Vous avez essayé '{$level}'",
            ]);
        }
    }

    public function getBySchool(int $schoolId, ?string $academicYear = null, bool $includeArchived = false): \Illuminate\Database\Eloquent\Collection
    {
        return $this->classRepository->getBySchool($schoolId, $academicYear, $includeArchived);
    }

    public function getByLevel(int $schoolId, string $level, ?string $academicYear = null): \Illuminate\Database\Eloquent\Collection
    {
        return $this->classRepository->getByLevel($schoolId, $level, $academicYear);
    }
}
