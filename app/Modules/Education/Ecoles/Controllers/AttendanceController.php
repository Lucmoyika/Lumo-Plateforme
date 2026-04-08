<?php

namespace App\Modules\Education\Ecoles\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\Requests\AttendanceRequest;
use App\Modules\Education\Ecoles\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(private readonly AttendanceService $attendanceService) {}

    /**
     * Mark attendance for one or more students.
     */
    public function record(AttendanceRequest $request): JsonResponse
    {
        $records = $this->attendanceService->record($request->validated('records'));

        return $this->successResponse($records, 'Présences enregistrées.', 201);
    }

    /**
     * Get attendance records for a class on a given date.
     */
    public function getByClass(Request $request): JsonResponse
    {
        $request->validate([
            'class_id' => ['required', 'exists:school_classes,id'],
            'date'     => ['nullable', 'date'],
        ]);

        $records = $this->attendanceService->getByClass(
            (int) $request->get('class_id'),
            $request->get('date')
        );

        return $this->successResponse($records, 'Présences récupérées.');
    }

    /**
     * Get attendance history for a student.
     */
    public function getByStudent(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'from'       => ['nullable', 'date'],
            'to'         => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $records = $this->attendanceService->getByStudent(
            (int) $request->get('student_id'),
            $request->get('from'),
            $request->get('to')
        );

        return $this->successResponse($records, 'Présences récupérées.');
    }

    /**
     * Get attendance statistics report for a student.
     */
    public function getReport(Request $request): JsonResponse
    {
        $request->validate(['student_id' => ['required', 'exists:students,id']]);

        $report = $this->attendanceService->getReport((int) $request->get('student_id'));

        return $this->successResponse($report, 'Rapport de présence généré.');
    }
}
