<?php

namespace App\Modules\Education\Universites\Services;

use App\Modules\Education\Universites\Repositories\FacultyRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class FacultyService extends BaseService
{
    public function __construct(protected FacultyRepository $facultyRepository)
    {
        parent::__construct($facultyRepository);
    }

    public function getByUniversity(int $universityId): Collection
    {
        return $this->facultyRepository->getByUniversity($universityId);
    }
}
