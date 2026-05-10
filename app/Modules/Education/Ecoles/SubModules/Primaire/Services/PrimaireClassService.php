<?php

namespace App\Modules\Education\Ecoles\SubModules\Primaire\Services;

use App\Modules\Education\Ecoles\SubModules\Primaire\Repositories\PrimaireClassRepository;
use App\Services\BaseService;
use Illuminate\Validation\ValidationException;

class PrimaireClassService extends BaseService
{
    private const PRIMAIRE_LEVELS = ['1er', '2e', '3e', '4e', '5e', '6e'];

    public function __construct(protected PrimaireClassRepository $classRepository)
    {
        parent::__construct($classRepository);
    }

    public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        $this->validateLevel($data);
        return parent::create($data);
    }

    private function validateLevel(array $data): void
    {
        if (isset($data['level']) && !in_array($data['level'], self::PRIMAIRE_LEVELS)) {
            throw ValidationException::withMessages([
                'level' => 'Primaire: ' . implode(', ', self::PRIMAIRE_LEVELS),
            ]);
        }
    }

    public function getBySchool(int $schoolId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->classRepository->getBySchool($schoolId);
    }
}
