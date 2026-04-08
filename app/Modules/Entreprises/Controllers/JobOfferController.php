<?php

namespace App\Modules\Entreprises\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobOfferController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return $this->successResponse([], 'Offres d\'emploi récupérées.');
    }

    public function store(Request $request): JsonResponse
    {
        return $this->successResponse([], 'Offre créée.', 201);
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse([], 'Offre récupérée.');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return $this->successResponse([], 'Offre mise à jour.');
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->successResponse(null, 'Offre supprimée.');
    }
}
