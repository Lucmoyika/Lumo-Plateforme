<?php

namespace App\Modules\Education\Ecoles\SubModules\Primaire\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\SubModules\Primaire\Services\PrimaireTeacherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PrimaireTeacherController extends Controller
{
    public function __construct(private readonly PrimaireTeacherService $teacherService) {}

    public function index(Request $request): JsonResponse
    {
        $schoolId = (int) ($request->route('school') ?? 0);

        if ($schoolId) {
            $teachers = $this->teacherService->getAll($schoolId);
            return $this->successResponse($teachers, '✅ Enseignants Primaire récupérés.');
        }

        return $this->errorResponse('❌ school_id requis', [], 400);
    }

    public function store(Request $request): JsonResponse
    {
        $schoolId = (int) ($request->route('school') ?? 0);
        $data = $request->validated();

        if ($schoolId > 0) {
            $data['school_id'] = $schoolId;
        }

        try {
            $teacher = $this->teacherService->create($data);
            return $this->successResponse($teacher, '✅ Enseignant Primaire créé.', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 422);
        }
    }
}
