<?php

namespace App\Modules\Emploi\Repositories;

use App\Modules\Emploi\Models\JobOffer;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class JobOfferRepository extends BaseRepository
{
    public function __construct(JobOffer $model)
    {
        parent::__construct($model);
    }

    public function search(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query()->where('status', 'active');

        if (!empty($filters['keyword'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['keyword'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['keyword'] . '%');
            });
        }

        if (!empty($filters['city'])) {
            $query->where('location', 'like', '%' . $filters['city'] . '%');
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['company_id'])) {
            $query->where('company_id', $filters['company_id']);
        }

        if (!empty($filters['min_salary'])) {
            $query->where('salary_min', '>=', $filters['min_salary']);
        }

        return $query->with(['company'])->latest()->paginate($perPage);
    }

    public function getMatchingOffers(array $skills, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->where('status', 'active')
            ->where(function ($query) use ($skills) {
                foreach ($skills as $skill) {
                    $query->orWhereJsonContains('required_skills', $skill);
                }
            })
            ->with(['company'])
            ->latest()
            ->paginate($perPage);
    }
}
