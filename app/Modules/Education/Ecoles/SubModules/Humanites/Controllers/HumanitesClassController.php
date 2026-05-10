<?php

namespace App\Modules\Education\Ecoles\SubModules\Humanites\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\SubModules\Humanites\Requests\HumanitesClassRequest;
use App\Modules\Education\Ecoles\SubModules\Humanites\Services\HumanitesClassService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HumanitesClassController extends Controller
{
    public function __construct(private readonly HumanitesClassService $classService) {}

    public function index(Request $request): JsonResponse
    {
        $schoolId = (int) ($request->route('school') ?? 0);
        if (!$schoolId) {
            return $this->errorResponse('❌ school_id requis', [], 400);
        }

        $classes = $this->classService->getBySchool($schoolId);
        return $this->successResponse($classes, '✅ Classes Humanités récupérées.');
    }

    public function store(HumanitesClassRequest $request): JsonResponse
    {
        $schoolId = (int) ($request->route('school') ?? 0);
        $data = $request->validated();

        if ($schoolId > 0) {
            $data['school_id'] = $schoolId;
        }

        try {
            $class = $this->classService->create($data);
            return $this->successResponse($class, '✅ Classe Humanités créée.', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 422);
        }
    }
}
