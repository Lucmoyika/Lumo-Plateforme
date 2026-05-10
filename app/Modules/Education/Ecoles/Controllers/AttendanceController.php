<?php

namespace App\Modules\Education\Ecoles\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\Models\SchoolClass;
use App\Modules\Education\Ecoles\Models\Student;
use App\Modules\Education\Ecoles\Requests\AttendanceRequest;
use App\Modules\Education\Ecoles\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(private readonly AttendanceService $attendanceService) {}

    /**
     * Compatibility endpoint for GET /attendance.
     */
    public function index(Request $request): JsonResponse
    {
        return $this->getByClass($request);
    }

    /**
     * Compatibility endpoint for POST /attendance.
     */
    public function store(AttendanceRequest $request): JsonResponse
    {
        return $this->record($request);
    }

    /**
     * Mark attendance for one or more students.
     */
    public function record(AttendanceRequest $request): JsonResponse
    {
        $recordsPayload = $request->validated('records');
        $schoolId = (int) ($request->route('school') ?? 0);

        if ($schoolId > 0) {
            foreach ($recordsPayload as $record) {
                $class = SchoolClass::query()->find((int) $record['class_id']);
                $student = Student::query()->find((int) $record['student_id']);

                if (!$class || (int) $class->school_id !== $schoolId) {
                    return $this->errorResponse('Classe hors périmètre établissement.', [], 403);
                }

                if (!$student || (int) $student->school_id !== $schoolId) {
                    return $this->errorResponse('Etudiant hors périmètre établissement.', [], 403);
                }

                if ((int) $student->class_id !== (int) $class->id) {
                    return $this->errorResponse('L\'étudiant ne dépend pas de la classe fournie.', [], 422);
                }
            }
        }

        $records = $this->attendanceService->record($recordsPayload);

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

        $classId = (int) $request->get('class_id');
        $schoolId = (int) ($request->route('school') ?? 0);

        if ($schoolId > 0) {
            $class = SchoolClass::query()->find($classId);
            if (!$class || (int) $class->school_id !== $schoolId) {
                return $this->errorResponse('Classe hors périmètre établissement.', [], 403);
            }
        }

        $records = $this->attendanceService->getByClass(
            $classId,
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

        $studentId = (int) $request->get('student_id');
        $schoolId = (int) ($request->route('school') ?? 0);

        if ($schoolId > 0) {
            $student = Student::query()->find($studentId);
            if (!$student || (int) $student->school_id !== $schoolId) {
                return $this->errorResponse('Etudiant hors périmètre établissement.', [], 403);
            }
        }

        $records = $this->attendanceService->getByStudent(
            $studentId,
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

        $studentId = (int) $request->get('student_id');
        $schoolId = (int) ($request->route('school') ?? 0);

        if ($schoolId > 0) {
            $student = Student::query()->find($studentId);
            if (!$student || (int) $student->school_id !== $schoolId) {
                return $this->errorResponse('Etudiant hors périmètre établissement.', [], 403);
            }
        }

        $report = $this->attendanceService->getReport($studentId);

        return $this->successResponse($report, 'Rapport de présence généré.');
    }
}
