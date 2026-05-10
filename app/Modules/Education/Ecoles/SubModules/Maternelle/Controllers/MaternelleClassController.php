<?php

namespace App\Modules\Education\Ecoles\SubModules\Maternelle\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\SubModules\Maternelle\Requests\MaternelleClassRequest;
use App\Modules\Education\Ecoles\SubModules\Maternelle\Services\MaternelleClassService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaternelleClassController extends Controller
{
    public function __construct(private readonly MaternelleClassService $classService) {}

    public function index(Request $request): JsonResponse
    {
        $schoolId = (int) ($request->route('school') ?? $request->get('school_id') ?? 0);

        if ($schoolId) {
            $classes = $this->classService->getBySchool($schoolId);
            return $this->successResponse($classes, '✅ Classes Maternelle récupérées.');
        }

        return $this->errorResponse('❌ school_id requis', [], 400);
    }

    public function store(MaternelleClassRequest $request): JsonResponse
    {
        $schoolId = (int) ($request->route('school') ?? $request->get('school_id') ?? 0);
        $data = $request->validated();

        if ($schoolId > 0) {
            $data['school_id'] = $schoolId;
        }

        try {
            $class = $this->classService->create($data);
            return $this->successResponse($class, '✅ Classe Maternelle créée.', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('❌ Erreur: ' . $e->getMessage(), [], 422);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $existing = $this->classService->getById($id);
        if (!$existing) {
            return $this->errorResponse('❌ Classe introuvable.', [], 404);
        }

        $this->classService->delete($id);
        return $this->successResponse(null, '✅ Classe archivée.');
    }
}
