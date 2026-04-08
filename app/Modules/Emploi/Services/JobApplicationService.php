<?php

namespace App\Modules\Emploi\Services;

use App\Modules\Emploi\Models\JobApplication;
use App\Modules\Emploi\Repositories\JobApplicationRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class JobApplicationService extends BaseService
{
    public function __construct(protected JobApplicationRepository $applicationRepository)
    {
        parent::__construct($applicationRepository);
    }

    public function apply(int $userId, int $offerId, array $data): JobApplication
    {
        $existing = $this->applicationRepository->findByUserAndOffer($userId, $offerId);

        if ($existing) {
            throw new \InvalidArgumentException('Vous avez déjà postulé à cette offre.');
        }

        return $this->applicationRepository->create(array_merge($data, [
            'user_id'      => $userId,
            'job_offer_id' => $offerId,
            'status'       => 'pending',
        ]));
    }

    public function withdraw(int $applicationId, int $userId): bool
    {
        $application = $this->applicationRepository->findOrFail($applicationId);

        if ($application->user_id !== $userId) {
            throw new \InvalidArgumentException('Action non autorisée.');
        }

        if (!in_array($application->status, ['pending', 'reviewing'])) {
            throw new \InvalidArgumentException('Impossible de retirer cette candidature.');
        }

        return $this->applicationRepository->delete($applicationId);
    }

    public function updateStatus(int $applicationId, string $status): JobApplication
    {
        return $this->applicationRepository->update($applicationId, ['status' => $status]);
    }

    public function getByOffer(int $offerId): Collection
    {
        return $this->applicationRepository->getByOffer($offerId);
    }

    public function getByUser(int $userId): Collection
    {
        return $this->applicationRepository->getByUser($userId);
    }
}
