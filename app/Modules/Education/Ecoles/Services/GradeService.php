<?php

namespace App\Modules\Education\Ecoles\Services;

use App\Modules\Education\Ecoles\Repositories\GradeRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class GradeService extends BaseService
{
    public function __construct(protected GradeRepository $gradeRepository)
    {
        parent::__construct($gradeRepository);
    }

    public function storeOrUpdate(array $data): \App\Modules\Education\Ecoles\Models\Grade
    {
        return $this->gradeRepository->upsert($data);
    }

    public function getBulletin(int $studentId, string $period): array
    {
        $grades  = $this->gradeRepository->getByStudent($studentId, $period);
        $average = $grades->avg('value') ?? 0;

        return [
            'grades'  => $grades,
            'period'  => $period,
            'average' => round($average, 2),
            'passed'  => $average >= 10,
        ];
    }

    public function getReport(int $classId, string $period): array
    {
        $grades = $this->gradeRepository->getByClass($classId, $period);

        return [
            'grades'      => $grades,
            'class_avg'   => round($grades->avg('value') ?? 0, 2),
            'highest'     => $grades->max('value'),
            'lowest'      => $grades->min('value'),
        ];
    }

    public function getStats(int $classId): array
    {
        $grades = $this->gradeRepository->getByClass($classId);

        return [
            'total_students' => $grades->pluck('student_id')->unique()->count(),
            'average'        => round($grades->avg('value') ?? 0, 2),
            'pass_rate'      => $grades->count() > 0
                ? round($grades->where('value', '>=', 10)->count() / $grades->count() * 100, 2)
                : 0,
        ];
    }
}
