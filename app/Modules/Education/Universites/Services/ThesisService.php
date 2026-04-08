<?php

namespace App\Modules\Education\Universites\Services;

use App\Modules\Education\Universites\Repositories\ThesisRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class ThesisService extends BaseService
{
    public function __construct(protected ThesisRepository $thesisRepository)
    {
        parent::__construct($thesisRepository);
    }

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->thesisRepository->paginateWithFilters($filters, $perPage);
    }

    public function updateStatus(int $id, string $status, ?string $feedback = null): \Illuminate\Database\Eloquent\Model
    {
        $data = ['status' => $status];

        if ($feedback !== null) {
            $data['feedback'] = $feedback;
        }

        return $this->thesisRepository->update($id, $data);
    }
}
