<?php

namespace App\Modules\Education\Universites\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UniversityStudentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return $this->successResponse([], 'Étudiants récupérés.');
    }

    public function store(Request $request): JsonResponse
    {
        return $this->successResponse([], 'Étudiant créé.', 201);
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse([], 'Étudiant récupéré.');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return $this->successResponse([], 'Étudiant mis à jour.');
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->successResponse(null, 'Étudiant supprimé.');
    }
}
