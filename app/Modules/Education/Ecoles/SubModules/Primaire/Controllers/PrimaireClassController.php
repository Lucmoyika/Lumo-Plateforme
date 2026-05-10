<?php

namespace App\Modules\Education\Ecoles\SubModules\Primaire\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\SubModules\Primaire\Requests\PrimaireClassRequest;
use App\Modules\Education\Ecoles\SubModules\Primaire\Services\PrimaireClassService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PrimaireClassController extends Controller
{
    public function __construct(private readonly PrimaireClassService $classService) {}

    public function index(Request $request): JsonResponse
    {
        $schoolId = (int) ($request->route('school') ?? $request->get('school_id') ?? 0);

        if ($schoolId) {
            $classes = $this->classService->getBySchool($schoolId);
            return $this->successResponse($classes, '✅ Classes Primaire récupérées.');
        }

        return $this->errorResponse('❌ school_id requis', [], 400);
    }

    public function store(PrimaireClassRequest $request): JsonResponse
    {
        $schoolId = (int) ($request->route('school') ?? $request->get('school_id') ?? 0);
        $data = $request->validated();

        if ($schoolId > 0) {
            $data['school_id'] = $schoolId;
        }

        try {
            $class = $this->classService->create($data);
            return $this->successResponse($class, '✅ Classe Primaire créée.', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 422);
        }
    }
}
