<?php

namespace App\Modules\Education\Ecoles\SubModules\Secondaire\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\SubModules\Secondaire\Requests\SecondaireClassRequest;
use App\Modules\Education\Ecoles\SubModules\Secondaire\Services\SecondaireClassService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SecondaireClassController extends Controller
{
    public function __construct(private readonly SecondaireClassService $classService) {}

    public function index(Request $request): JsonResponse
    {
        $schoolId = (int) ($request->route('school') ?? 0);
        if (!$schoolId) {
            return $this->errorResponse('❌ school_id requis', [], 400);
        }

        $classes = $this->classService->getBySchool($schoolId);
        return $this->successResponse($classes, '✅ Classes Secondaire récupérées.');
    }

    public function store(SecondaireClassRequest $request): JsonResponse
    {
        $schoolId = (int) ($request->route('school') ?? 0);
        $data = $request->validated();

        if ($schoolId > 0) {
            $data['school_id'] = $schoolId;
        }

        try {
            $class = $this->classService->create($data);
            return $this->successResponse($class, '✅ Classe Secondaire créée.', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 422);
        }
    }
}
