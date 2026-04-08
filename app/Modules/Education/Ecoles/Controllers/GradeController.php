<?php

namespace App\Modules\Education\Ecoles\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\Requests\GradeRequest;
use App\Modules\Education\Ecoles\Services\GradeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function __construct(private readonly GradeService $gradeService) {}

    /**
     * Store or update a grade entry.
     */
    public function store(GradeRequest $request): JsonResponse
    {
        $grade = $this->gradeService->storeOrUpdate($request->validated());

        return $this->successResponse($grade, 'Note enregistrée.', 201);
    }

    /**
     * Update an existing grade.
     */
    public function update(GradeRequest $request, int $id): JsonResponse
    {
        $grade = $this->gradeService->update($id, $request->validated());

        return $this->successResponse($grade, 'Note mise à jour.');
    }

    /**
     * Get report card (bulletin) for a student for a given period.
     */
    public function getBulletin(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'period'     => ['required', 'string'],
        ]);

        $bulletin = $this->gradeService->getBulletin(
            (int) $request->get('student_id'),
            $request->get('period')
        );

        return $this->successResponse($bulletin, 'Bulletin récupéré.');
    }

    /**
     * Get class-level grade report for a period.
     */
    public function getReport(Request $request): JsonResponse
    {
        $request->validate([
            'class_id' => ['required', 'exists:school_classes,id'],
            'period'   => ['required', 'string'],
        ]);

        $report = $this->gradeService->getReport(
            (int) $request->get('class_id'),
            $request->get('period')
        );

        return $this->successResponse($report, 'Rapport généré.');
    }

    /**
     * Get aggregate statistics for a class.
     */
    public function getStats(Request $request): JsonResponse
    {
        $request->validate(['class_id' => ['required', 'exists:school_classes,id']]);

        $stats = $this->gradeService->getStats((int) $request->get('class_id'));

        return $this->successResponse($stats, 'Statistiques calculées.');
    }
}
