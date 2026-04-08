<?php

namespace App\Modules\Emploi\Services;

use App\Modules\Emploi\Repositories\JobOfferRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class JobOfferService extends BaseService
{
    public function __construct(protected JobOfferRepository $jobOfferRepository)
    {
        parent::__construct($jobOfferRepository);
    }

    public function search(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->jobOfferRepository->search($filters, $perPage);
    }

    public function recommend(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        $user   = \App\Models\User::findOrFail($userId);
        $skills = $user->skills ?? [];

        if (empty($skills)) {
            return $this->jobOfferRepository->search([], $perPage);
        }

        return $this->jobOfferRepository->getMatchingOffers($skills, $perPage);
    }
}
