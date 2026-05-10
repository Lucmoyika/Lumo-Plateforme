<?php

namespace App\Modules\Education\Ecoles\SubModules\Humanites\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\SubModules\Humanites\Services\HumanitesTeacherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HumanitesTeacherController extends Controller
{
    public function __construct(private readonly HumanitesTeacherService $teacherService) {}

    public function index(Request $request): JsonResponse
    {
        $schoolId = (int) ($request->route('school') ?? 0);
        if (!$schoolId) {
            return $this->errorResponse('❌ school_id requis', [], 400);
        }

        $teachers = $this->teacherService->getAll($schoolId);
        return $this->successResponse($teachers, '✅ Enseignants Humanités récupérés.');
    }
}
