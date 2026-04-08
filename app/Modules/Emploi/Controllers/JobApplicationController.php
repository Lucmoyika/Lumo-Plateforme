<?php

namespace App\Modules\Emploi\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Emploi\Services\JobApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function __construct(private readonly JobApplicationService $applicationService) {}

    public function apply(Request $request): JsonResponse
    {
        $data = $request->validate([
            'job_offer_id' => ['required','exists:job_offers,id'],
            'cover_letter' => ['nullable','string'],
            'cv_path'      => ['nullable','string'],
        ]);
        try {
            $app = $this->applicationService->apply($request->user()->id, $data['job_offer_id'], $data);
            return $this->successResponse($app, 'Candidature envoyée.', 201);
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), [], 409);
        }
    }

    public function withdraw(Request $request, int $id): JsonResponse
    {
        try {
            $this->applicationService->withdraw($id, $request->user()->id);
            return $this->successResponse(null, 'Candidature retirée.');
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), [], 400);
        }
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $data = $request->validate(['status' => ['required','string','in:pending,reviewing,interviewed,accepted,rejected']]);
        $app  = $this->applicationService->updateStatus($id, $data['status']);
        return $this->successResponse($app, 'Statut mis à jour.');
    }

    public function getByOffer(Request $request): JsonResponse
    {
        $request->validate(['job_offer_id' => ['required','exists:job_offers,id']]);
        $apps = $this->applicationService->getByOffer((int)$request->get('job_offer_id'));
        return $this->successResponse($apps, 'Candidatures récupérées.');
    }

    public function getByUser(Request $request): JsonResponse
    {
        $apps = $this->applicationService->getByUser($request->user()->id);
        return $this->successResponse($apps, 'Mes candidatures récupérées.');
    }
}
