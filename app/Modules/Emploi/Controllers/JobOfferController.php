<?php

namespace App\Modules\Emploi\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Emploi\Services\JobOfferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobOfferController extends Controller
{
    public function __construct(private readonly JobOfferService $jobOfferService) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->jobOfferService->search($request->only(['keyword','city','type','company_id','min_salary']), (int)$request->get('per_page',15));
        return $this->paginatedResponse($paginator, 'Offres récupérées.');
    }

    public function show(int $id): JsonResponse
    {
        $offer = $this->jobOfferService->getById($id, ['company','applications']);
        if (!$offer) return $this->errorResponse('Offre introuvable.', [], 404);
        return $this->successResponse($offer, 'Offre récupérée.');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'company_id'       => ['required','exists:companies,id'],
            'title'            => ['required','string','max:255'],
            'description'      => ['required','string'],
            'requirements'     => ['nullable','string'],
            'type'             => ['required','string','in:full_time,part_time,internship,freelance'],
            'location'         => ['nullable','string','max:255'],
            'salary_min'       => ['nullable','numeric','min:0'],
            'salary_max'       => ['nullable','numeric','min:0','gte:salary_min'],
            'deadline'         => ['nullable','date','after:today'],
            'required_skills'  => ['nullable','array'],
        ]);
        $offer = $this->jobOfferService->create($data);
        return $this->successResponse($offer, 'Offre créée.', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'title'           => ['sometimes','string','max:255'],
            'description'     => ['sometimes','string'],
            'type'            => ['sometimes','string','in:full_time,part_time,internship,freelance'],
            'location'        => ['nullable','string','max:255'],
            'salary_min'      => ['nullable','numeric','min:0'],
            'salary_max'      => ['nullable','numeric','min:0'],
            'deadline'        => ['nullable','date'],
            'status'          => ['nullable','string','in:active,closed,draft'],
            'required_skills' => ['nullable','array'],
        ]);
        return $this->successResponse($this->jobOfferService->update($id, $data), 'Offre mise à jour.');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->jobOfferService->delete($id);
        return $this->successResponse(null, 'Offre supprimée.');
    }

    public function search(Request $request): JsonResponse
    {
        $paginator = $this->jobOfferService->search($request->all(), (int)$request->get('per_page',15));
        return $this->paginatedResponse($paginator, 'Résultats de recherche.');
    }

    public function recommend(Request $request): JsonResponse
    {
        $paginator = $this->jobOfferService->recommend($request->user()->id);
        return $this->paginatedResponse($paginator, 'Offres recommandées.');
    }
}
