<?php

namespace App\Modules\Education\Ecoles\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Education\Ecoles\Models\School;
use App\Modules\Education\Ecoles\Requests\SchoolRequest;
use App\Modules\Education\Ecoles\Services\SchoolService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function __construct(private readonly SchoolService $schoolService) {}

    /**
     * List schools with optional filters (level, city, status).
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'level' => ['nullable', 'string', 'in:maternelle,primaire,secondaire,humanites'],
            'education_submodule' => ['nullable', 'string', 'in:mp,sh,full'],
            'subscription_status' => ['nullable', 'string', 'in:trial,active,expired,suspended'],
            'city' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
        ]);

        $filters = $request->only(['level', 'education_submodule', 'subscription_status', 'city', 'status', 'search']);
        $perPage = (int) $request->get('per_page', 15);

        $paginator = $this->schoolService->list($filters, $perPage, $request->user());

        return $this->paginatedResponse($paginator, 'Écoles récupérées.');
    }

    /**
     * Show a single school.
     *
     * Authorization is strict: only school director or school staff.
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $school = $this->schoolService->getById($id, ['director', 'classes']);

        if (!$school) {
            return $this->errorResponse('École introuvable.', [], 404);
        }

        $user = $request->user();
        $isSchoolDirector = $user && (int) $school->director_id === (int) $user->id;
        $isSchoolStaff = $user && (int) $school->id === (int) ($user->school_id ?? 0);

        if (!$isSchoolDirector && !$isSchoolStaff) {
            return $this->errorResponse('Accès refusé : vous n\'avez pas les droits sur cette école.', [], 403);
        }

        return $this->successResponse($school, 'École récupérée.');
    }

    /**
     * Create a new school with optional director creation/assignment.
     */
    public function store(SchoolRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if (!empty($validated['director_id'])) {
            $director = User::query()->find((int) $validated['director_id']);
            if (!$director || $director->hasAnyRole(['super_admin', 'admin'])) {
                return $this->errorResponse('Le directeur doit être un compte scolaire, pas un administrateur global.', [], 422);
            }
        }

        // Si on a des données pour créer un directeur, utiliser le workflow d'onboarding
        if ($request->has('create_director')) {
            $school = $this->schoolService->createWithDirector($validated);
        } else {
            $school = $this->schoolService->create($validated);
            $this->assignSchoolAdminRole($school->director_id);
        }

        return $this->successResponse($school->load('director'), 'École créée avec succès.', 201);
    }

    /**
     * Update an existing school.
     */
    public function update(SchoolRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        if (!empty($validated['director_id'])) {
            $director = User::query()->find((int) $validated['director_id']);
            if (!$director || $director->hasAnyRole(['super_admin', 'admin'])) {
                return $this->errorResponse('Le directeur doit être un compte scolaire, pas un administrateur global.', [], 422);
            }
        }

        $school = $this->schoolService->update($id, $validated);

        $this->assignSchoolAdminRole($school->director_id);

        return $this->successResponse($school->load('director'), 'École mise à jour.');
    }

    /**
     * Soft-delete a school.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->schoolService->delete($id);

        return $this->successResponse(null, 'École supprimée.');
    }

    /**
     * Restore a soft-deleted school.
     */
    public function restore(int $id): JsonResponse
    {
        $school = $this->schoolService->restore($id);

        return $this->successResponse($school, 'École restaurée.');
    }

    /**
     * Liste les utilisateurs disponibles pour être directeur d'une école.
     * Super-admin uniquement.
     */
    public function availableDirectors(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $perPage = (int) $request->get('per_page', 15);

        $query = User::query()
            ->select('id', 'name', 'email', 'phone', 'role')
            ->where('status', 'active')
            ->whereNotIn('role', ['super_admin', 'admin']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate($perPage);

        return $this->paginatedResponse($users, 'Utilisateurs disponibles récupérés.');
    }

    /**
     * Catalogue des sous-modules Ecoles pour le contexte RDC.
     */
    public function submodulesCatalog(): JsonResponse
    {
        return $this->successResponse(
            array_values(config('school_modules.submodules', [])),
            'Sous-modules école récupérés.'
        );
    }

    /**
     * Catalogue des plans de licence (mensuel, annuel, custom) + essai.
     */
    public function licensePlansCatalog(Request $request): JsonResponse
    {
        $submodule = $request->get('education_submodule');
        $plans = array_values(config('school_modules.plans', []));

        if ($submodule) {
            $plans = array_values(array_filter($plans, fn(array $plan) => ($plan['submodule_key'] ?? null) === $submodule));
        }

        return $this->successResponse([
            'trial_days' => (int) config('school_modules.trial_days', 30),
            'plans' => $plans,
        ], 'Plans de licence récupérés.');
    }

    /**
     * Etat de licence d'une école (mobile/web).
     */
    public function licenseStatus(School $school): JsonResponse
    {
        $now = Carbon::now();

        $isTrialActive = $school->subscription_status === 'trial'
            && $school->trial_ends_at
            && $school->trial_ends_at->greaterThan($now);

        $isSubscriptionActive = $school->subscription_status === 'active'
            && $school->subscription_ends_at
            && $school->subscription_ends_at->greaterThan($now);

        return $this->successResponse([
            'education_submodule' => $school->education_submodule,
            'license_plan_code' => $school->license_plan_code,
            'subscription_status' => $school->subscription_status,
            'billing_duration_months' => $school->billing_duration_months,
            'license_price_cdf' => $school->license_price_cdf,
            'trial_ends_at' => $school->trial_ends_at,
            'subscription_starts_at' => $school->subscription_starts_at,
            'subscription_ends_at' => $school->subscription_ends_at,
            'mobile_access_enabled' => $school->mobile_access_enabled,
            'is_trial_active' => $isTrialActive,
            'is_subscription_active' => $isSubscriptionActive,
        ], 'Statut de licence récupéré.');
    }

    private function assignSchoolAdminRole(?int $directorId): void
    {
        if (!$directorId) {
            return;
        }

        $director = User::query()->find($directorId);
        if (!$director || $director->hasAnyRole(['super_admin', 'admin'])) {
            return;
        }

        $director->assignRole('school_admin');

        if ($director->role !== 'school_admin') {
            $director->role = 'school_admin';
            $director->save();
        }
    }
}
