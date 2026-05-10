<?php

namespace App\Modules\Education\Ecoles\Services;

use App\Models\User;
use App\Modules\Education\Ecoles\Repositories\SchoolRepository;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SchoolService extends BaseService
{
    public function __construct(protected SchoolRepository $schoolRepository)
    {
        parent::__construct($schoolRepository);
    }

    public function restore(int $id)
    {
        return $this->schoolRepository->restore($id);
    }

    public function list(array $filters = [], int $perPage = 15, ?User $user = null): LengthAwarePaginator
    {
        return $this->schoolRepository->paginateWithFilters($filters, $perPage, $user);
    }

    public function levelTypesDescription(array $levelTypes = []): string
    {
        $labels = [
            'maternelle' => 'Maternelle',
            'primaire' => 'Primaire',
            'secondaire' => 'Secondaire',
            'humanites' => 'Humanités',
        ];

        $names = array_map(fn($level) => $labels[$level] ?? $level, $levelTypes);
        return implode(', ', $names) ?: 'Aucun niveau';
    }

    /**
     * Crée une école et gère optionnellement la création du directeur.
     * 
     * Supports :
     * 1. director_id: assigner un utilisateur existant
     * 2. create_director: créer un nouveau directeur avec name/email/phone
     */
    public function createWithDirector(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $directorData = $data['create_director'] ?? null;
            unset($data['create_director']);

            $data = $this->applyLicensingData($data);

            if ($directorData && isset($directorData['name'], $directorData['email'])) {
                $password = Str::random(12);

                $director = User::create([
                    'name'     => $directorData['name'],
                    'email'    => $directorData['email'],
                    'phone'    => $directorData['phone'] ?? null,
                    'password' => bcrypt($password),
                    'role'     => 'school_admin',
                    'status'   => 'active',
                ]);

                $director->assignRole('school_admin');
                $data['director_id'] = $director->id;

                // TODO: Envoyer email au directeur avec les instructions et password temporaire
                // $this->sendDirectorWelcomeEmail($director, $password);
            }

            return $this->create($data);
        });
    }

    public function create(array $data): Model
    {
        return parent::create($this->applyLicensingData($data));
    }

    public function update(int $id, array $data): Model
    {
        return parent::update($id, $this->applyLicensingData($data, false));
    }

    private function applyLicensingData(array $data, bool $isCreate = true): array
    {
        $now = Carbon::now();
        $trialDays = (int) config('school_modules.trial_days', 30);
        $submodule = $data['education_submodule'] ?? null;
        $planCode = $data['license_plan_code'] ?? null;
        $startTrial = (bool) ($data['start_trial'] ?? true);

        if ($submodule) {
            $data['mobile_access_enabled'] = $data['mobile_access_enabled']
                ?? (bool) config("school_modules.submodules.{$submodule}.mobile_enabled", true);
        }

        // Plan catalogue: mensuel/annuel (ou autres durees preconfigurees)
        if ($planCode) {
            $plan = config("school_modules.plans.{$planCode}");
            if (is_array($plan)) {
                $months = (int) ($plan['duration_months'] ?? 0);
                $data['billing_duration_months'] = $months > 0 ? $months : null;
                $data['license_price_cdf'] = (int) ($plan['price_cdf'] ?? 0);
                $data['subscription_status'] = 'active';
                $data['subscription_starts_at'] = $now;
                $data['subscription_ends_at'] = $months > 0 ? $now->copy()->addMonths($months) : null;
                $data['trial_ends_at'] = null;
                $data['mobile_access_enabled'] = (bool) ($plan['mobile_enabled'] ?? true);
            }
        }

        // Duree personnalisee (quelques mois/annees)
        if (!empty($data['custom_duration_months']) && !empty($data['custom_price_cdf'])) {
            $months = (int) $data['custom_duration_months'];
            $data['billing_duration_months'] = $months;
            $data['license_price_cdf'] = (int) $data['custom_price_cdf'];
            $data['subscription_status'] = 'active';
            $data['subscription_starts_at'] = $now;
            $data['subscription_ends_at'] = $now->copy()->addMonths($months);
            $data['trial_ends_at'] = null;
            $data['license_plan_code'] = $data['license_plan_code'] ?? 'custom';
        }

        // Essai 1 mois si pas d'abonnement paye actif
        if (empty($data['subscription_status']) && ($isCreate || $startTrial)) {
            $data['subscription_status'] = 'trial';
            $data['trial_ends_at'] = $now->copy()->addDays($trialDays);
            $data['subscription_starts_at'] = null;
            $data['subscription_ends_at'] = null;
        }

        unset($data['start_trial'], $data['custom_duration_months'], $data['custom_price_cdf']);

        return $data;
    }
}

