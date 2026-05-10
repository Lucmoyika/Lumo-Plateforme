<?php

namespace App\Modules\Education\Ecoles\SubModules\Maternelle\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\SubModules\Maternelle\Requests\MaternelleTeacherRequest;
use App\Modules\Education\Ecoles\SubModules\Maternelle\Services\MaternelleTeacherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaternelleTeacherController extends Controller
{
    public function __construct(private readonly MaternelleTeacherService $teacherService) {}

    public function index(Request $request): JsonResponse
    {
        $schoolId = (int) ($request->route('school') ?? $request->get('school_id') ?? 0);

        if ($schoolId) {
            $teachers = $this->teacherService->getAll($schoolId);
            return $this->successResponse($teachers, '✅ Enseignantes Maternelle récupérées.');
        }

        return $this->errorResponse('❌ school_id requis', [], 400);
    }

    public function store(MaternelleTeacherRequest $request): JsonResponse
    {
        $schoolId = (int) ($request->route('school') ?? $request->get('school_id') ?? 0);
        $data = $request->validated();

        if ($schoolId > 0) {
            $data['school_id'] = $schoolId;
        }

        try {
            $teacher = $this->teacherService->create($data);
            return $this->successResponse($teacher, '✅ Enseignante Maternelle créée.', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse($e->errors()['gender'][0] ?? 'Erreur validation', $e->errors(), 422);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $teacher = $this->teacherService->getById($id);
        if (!$teacher) {
            return $this->errorResponse('❌ Enseignante introuvable.', [], 404);
        }

        $this->teacherService->delete($id);
        return $this->successResponse(null, '✅ Enseignante archivée.');
    }
}
