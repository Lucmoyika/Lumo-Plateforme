<?php

namespace App\Modules\Education\Ecoles\Services;

use App\Modules\Education\Ecoles\Repositories\TeacherRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TeacherService extends BaseService
{
    public function __construct(protected TeacherRepository $teacherRepository)
    {
        parent::__construct($teacherRepository);
    }

    public function listBySchool(int $schoolId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->teacherRepository->paginateBySchool($schoolId, $perPage);
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
