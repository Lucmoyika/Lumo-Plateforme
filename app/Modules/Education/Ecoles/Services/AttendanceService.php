<?php

namespace App\Modules\Education\Ecoles\Services;

use App\Modules\Education\Ecoles\Models\Attendance;
use App\Modules\Education\Ecoles\Repositories\AttendanceRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class AttendanceService extends BaseService
{
    public function __construct(protected AttendanceRepository $attendanceRepository)
    {
        parent::__construct($attendanceRepository);
    }

    public function record(array $records): array
    {
        $saved = [];
        foreach ($records as $record) {
            $saved[] = $this->attendanceRepository->upsertRecord($record);
        }

        return $saved;
    }

    public function getByClass(int $classId, ?string $date = null): Collection
    {
        return $this->attendanceRepository->getByClass($classId, $date);
    }

    public function getByStudent(int $studentId, ?string $from = null, ?string $to = null): Collection
    {
        return $this->attendanceRepository->getByStudent($studentId, $from, $to);
    }

    public function getReport(int $studentId): array
    {
        $records = $this->attendanceRepository->getByStudent($studentId);

        $total   = $records->count();
        $present = $records->where('status', 'present')->count();
        $absent  = $records->where('status', 'absent')->count();
        $late    = $records->where('status', 'late')->count();

        return [
            'total'           => $total,
            'present'         => $present,
            'absent'          => $absent,
            'late'            => $late,
            'attendance_rate' => $total > 0 ? round(($present / $total) * 100, 2) : 0,
        ];
    }
}
