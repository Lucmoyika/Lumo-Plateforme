<?php

namespace App\Modules\Education\Ecoles\SubModules\Maternelle\Services;

use App\Services\BaseService;
use Illuminate\Validation\ValidationException;

class MaternelleTeacherService extends BaseService
{
    public function __construct(private readonly \App\Modules\Education\Ecoles\SubModules\Maternelle\Repositories\MaternelleTeacherRepository $teacherRepository)
    {
        parent::__construct($teacherRepository);
    }

    public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        if (isset($data['gender']) && $data['gender'] !== 'F') {
            throw ValidationException::withMessages([
                'gender' => '🚫 Maternelle: FEMMES UNIQUEMENT. Vous avez essayé genre "' . $data['gender'] . '"',
            ]);
        }

        $data['gender'] = 'F';

        if (!isset($data['contract_type'])) {
            $data['contract_type'] = 'annual';
        }

        return parent::create($data);
    }

    public function update(int $id, array $data): \Illuminate\Database\Eloquent\Model
    {
        if (isset($data['gender']) && $data['gender'] !== 'F') {
            throw ValidationException::withMessages([
                'gender' => 'Maternelle: FEMMES UNIQUEMENT',
            ]);
        }

        return parent::update($id, $data);
    }

    public function getMainTeachers(int $schoolId, bool $includeArchived = false): \Illuminate\Database\Eloquent\Collection
    {
        return $this->teacherRepository->getMainTeachers($schoolId, $includeArchived);
    }

    public function getAssistants(int $schoolId, bool $includeArchived = false): \Illuminate\Database\Eloquent\Collection
    {
        return $this->teacherRepository->getAssistants($schoolId, $includeArchived);
    }

    public function getAll(int $schoolId, bool $includeArchived = false): \Illuminate\Database\Eloquent\Collection
    {
        return $this->teacherRepository->getBySchool($schoolId, $includeArchived);
    }
}
