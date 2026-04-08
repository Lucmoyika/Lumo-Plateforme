<?php

namespace App\Modules\Entreprises\Services;

use App\Modules\Entreprises\Repositories\CompanyRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyService extends BaseService
{
    public function __construct(protected CompanyRepository $companyRepository)
    {
        parent::__construct($companyRepository);
    }

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->companyRepository->paginateWithFilters($filters, $perPage);
    }

    public function verify(int $id): Model
    {
        return $this->companyRepository->update($id, [
            'is_verified'  => true,
            'verified_at'  => now(),
        ]);
    }

    public function getStats(int $id): array
    {
        $company = $this->companyRepository->findOrFail($id);

        return [
            'total_job_offers'       => $company->jobOffers()->count(),
            'active_job_offers'      => $company->jobOffers()->where('status', 'active')->count(),
            'total_applications'     => $company->jobOffers()->withCount('applications')->get()->sum('applications_count'),
            'employees'              => $company->employees()->count() ?? 0,
        ];
    }

    public function getJobOffers(int $id): \Illuminate\Database\Eloquent\Collection
    {
        $company = $this->companyRepository->findOrFail($id);

        return $company->jobOffers()->with(['applications'])->latest()->get();
    }
}
