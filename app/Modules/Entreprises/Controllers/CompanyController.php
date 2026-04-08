<?php

namespace App\Modules\Entreprises\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Entreprises\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(private readonly CompanyService $companyService) {}

    public function index(Request $request): JsonResponse
    {
        $filters   = $request->only(['sector', 'status', 'verified', 'search']);
        $paginator = $this->companyService->list($filters, (int) $request->get('per_page', 15));

        return $this->paginatedResponse($paginator, 'Entreprises récupérées.');
    }

    public function show(int $id): JsonResponse
    {
        $company = $this->companyService->getById($id, ['user', 'jobOffers']);

        if (!$company) {
            return $this->errorResponse('Entreprise introuvable.', [], 404);
        }

        return $this->successResponse($company, 'Entreprise récupérée.');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id'     => ['required', 'exists:users,id'],
            'name'        => ['required', 'string', 'max:255'],
            'sector'      => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'address'     => ['nullable', 'string', 'max:255'],
            'city'        => ['nullable', 'string', 'max:100'],
            'country'     => ['nullable', 'string', 'max:100'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'email'       => ['nullable', 'email'],
            'website'     => ['nullable', 'url'],
            'logo'        => ['nullable', 'string'],
            'size'        => ['nullable', 'string', 'in:micro,small,medium,large,enterprise'],
        ]);

        $company = $this->companyService->create($data);

        return $this->successResponse($company, 'Entreprise créée.', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name'        => ['sometimes', 'string', 'max:255'],
            'sector'      => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'address'     => ['nullable', 'string', 'max:255'],
            'city'        => ['nullable', 'string', 'max:100'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'email'       => ['nullable', 'email'],
            'website'     => ['nullable', 'url'],
            'size'        => ['nullable', 'string', 'in:micro,small,medium,large,enterprise'],
        ]);

        $company = $this->companyService->update($id, $data);

        return $this->successResponse($company, 'Entreprise mise à jour.');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->companyService->delete($id);

        return $this->successResponse(null, 'Entreprise supprimée.');
    }

    /**
     * Mark a company as verified.
     */
    public function verify(int $id): JsonResponse
    {
        $company = $this->companyService->verify($id);

        return $this->successResponse($company, 'Entreprise vérifiée.');
    }

    /**
     * Get company statistics.
     */
    public function getStats(int $id): JsonResponse
    {
        $stats = $this->companyService->getStats($id);

        return $this->successResponse($stats, 'Statistiques récupérées.');
    }

    /**
     * Get all job offers for a company.
     */
    public function getJobOffers(int $id): JsonResponse
    {
        $offers = $this->companyService->getJobOffers($id);

        return $this->successResponse($offers, 'Offres d\'emploi récupérées.');
    }
}
