<?php

namespace App\Modules\Education\Universites\Services;

use App\Modules\Education\Universites\Models\UniversityGrade;
use App\Modules\Education\Universites\Repositories\UniversityGradeRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class UniversityGradeService extends BaseService
{
    public function __construct(protected UniversityGradeRepository $gradeRepository)
    {
        parent::__construct($gradeRepository);
    }

    public function storeOrUpdate(array $data): UniversityGrade
    {
        return $this->gradeRepository->upsert($data);
    }

    /**
     * Get transcript (relevé de notes) for a student across all semesters.
     */
    public function getReleve(int $studentId, ?string $semester = null): Collection
    {
        return $this->gradeRepository->getByStudent($studentId, $semester);
    }

    /**
     * Build full academic transcript grouped by semester.
     */
    public function getTranscript(int $studentId): array
    {
        $grades = $this->gradeRepository->getByStudent($studentId);

        return $grades->groupBy('semester')->map(function ($semesterGrades) {
            return [
                'grades'  => $semesterGrades,
                'average' => round($semesterGrades->avg('value') ?? 0, 2),
                'credits' => $semesterGrades->sum(fn ($g) => $g->course->credits ?? 0),
                'gpa'     => $this->calculateGpaForGrades($semesterGrades),
            ];
        })->all();
    }

    /**
     * Calculate cumulative GPA for a student.
     */
    public function calculateGPA(int $studentId): float
    {
        $grades = $this->gradeRepository->getByStudent($studentId);

        return $this->calculateGpaForGrades($grades);
    }

    private function calculateGpaForGrades(Collection $grades): float
    {
        $totalPoints  = 0;
        $totalCredits = 0;

        foreach ($grades as $grade) {
            $credits       = $grade->course->credits ?? 1;
            $totalPoints  += $this->gradeToGpaPoints($grade->value) * $credits;
            $totalCredits += $credits;
        }

        return $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0.0;
    }

    private function gradeToGpaPoints(float $grade): float
    {
        // Convert 0-20 scale to 0-4 GPA scale
        return round(($grade / 20) * 4, 2);
    }
}
