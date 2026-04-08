<?php

namespace App\Modules\Education\Universites\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Universites\Services\UniversityGradeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UniversityGradeController extends Controller
{
    public function __construct(private readonly UniversityGradeService $gradeService) {}

    /**
     * Store or update a university grade.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:university_students,id'],
            'course_id'  => ['required', 'exists:courses,id'],
            'semester'   => ['required', 'string', 'max:20'],
            'value'      => ['required', 'numeric', 'min:0', 'max:20'],
            'type'       => ['nullable', 'string', 'in:exam,cc,tp,project'],
            'comment'    => ['nullable', 'string', 'max:500'],
        ]);

        $grade = $this->gradeService->storeOrUpdate($data);

        return $this->successResponse($grade, 'Note enregistrée.', 201);
    }

    /**
     * Update an existing university grade.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'value'   => ['required', 'numeric', 'min:0', 'max:20'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        $grade = $this->gradeService->update($id, $data);

        return $this->successResponse($grade, 'Note mise à jour.');
    }

    /**
     * Get transcript (relevé de notes) for a student.
     */
    public function getReleve(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => ['required', 'exists:university_students,id'],
            'semester'   => ['nullable', 'string'],
        ]);

        $releve = $this->gradeService->getReleve(
            (int) $request->get('student_id'),
            $request->get('semester')
        );

        return $this->successResponse($releve, 'Relevé de notes récupéré.');
    }

    /**
     * Get full academic transcript grouped by semester.
     */
    public function getTranscript(Request $request): JsonResponse
    {
        $request->validate(['student_id' => ['required', 'exists:university_students,id']]);

        $transcript = $this->gradeService->getTranscript((int) $request->get('student_id'));

        return $this->successResponse($transcript, 'Transcript récupéré.');
    }

    /**
     * Calculate cumulative GPA for a student.
     */
    public function calculateGPA(Request $request): JsonResponse
    {
        $request->validate(['student_id' => ['required', 'exists:university_students,id']]);

        $gpa = $this->gradeService->calculateGPA((int) $request->get('student_id'));

        return $this->successResponse(['gpa' => $gpa], 'GPA calculé.');
    }
}
