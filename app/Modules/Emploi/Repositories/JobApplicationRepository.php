<?php

namespace App\Modules\Emploi\Repositories;

use App\Modules\Emploi\Models\JobApplication;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class JobApplicationRepository extends BaseRepository
{
    public function __construct(JobApplication $model)
    {
        parent::__construct($model);
    }

    public function getByOffer(int $offerId): Collection
    {
        return $this->model->where('job_offer_id', $offerId)->with(['user'])->latest()->get();
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->with(['jobOffer.company'])->latest()->get();
    }

    public function findByUserAndOffer(int $userId, int $offerId): ?JobApplication
    {
        return $this->model->where('user_id', $userId)->where('job_offer_id', $offerId)->first();
    }
}
