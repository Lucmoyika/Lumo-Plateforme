<?php

namespace App\Modules\Education\Ecoles\Services;

use App\Modules\Education\Ecoles\Models\Student;
use App\Modules\Education\Ecoles\Repositories\StudentRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentService extends BaseService
{
    public function __construct(protected StudentRepository $studentRepository)
    {
        parent::__construct($studentRepository);
    }

    public function listBySchool(int $schoolId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->studentRepository->paginateBySchool($schoolId, $perPage);
    }

    public function importFromCsv(int $schoolId, UploadedFile $file): array
    {
        $rows    = array_map('str_getcsv', file($file->getRealPath()));
        $header  = array_shift($rows);
        $created = 0;
        $errors  = [];

        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                $data = array_combine($header, $row);

                if (empty($data['email']) || empty($data['name'])) {
                    $errors[] = "Ligne " . ($index + 2) . " : email ou nom manquant.";
                    continue;
                }

                $user = \App\Models\User::firstOrCreate(
                    ['email' => trim($data['email'])],
                    [
                        'name'     => trim($data['name']),
                        'password' => Hash::make('password123'),
                        'role'     => 'student',
                        'status'   => 'active',
                    ]
                );

                Student::firstOrCreate(
                    ['user_id' => $user->id, 'school_id' => $schoolId],
                    [
                        'student_number'  => $data['student_number'] ?? null,
                        'enrollment_date' => $data['enrollment_date'] ?? now()->toDateString(),
                    ]
                );

                $created++;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return ['created' => $created, 'errors' => $errors];
    }

    public function getGrades(int $studentId): Collection
    {
        return $this->studentRepository->getGrades($studentId);
    }

    public function getBulletin(int $studentId, string $period): array
    {
        $grades = $this->studentRepository->getGrades($studentId);

        $periodGrades = $grades->where('period', $period)->values();
        $average      = $periodGrades->avg('value') ?? 0;

        return [
            'student' => $this->studentRepository->find($studentId, ['*'], ['user']),
            'period'  => $period,
            'grades'  => $periodGrades,
            'average' => round($average, 2),
        ];
    }
}
