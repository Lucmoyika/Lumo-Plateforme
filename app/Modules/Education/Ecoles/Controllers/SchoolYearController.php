<?php

namespace App\Modules\Education\Ecoles\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\Models\Grade;
use App\Modules\Education\Ecoles\Models\Schedule;
use App\Modules\Education\Ecoles\Models\School;
use App\Modules\Education\Ecoles\Models\SchoolClass;
use App\Modules\Education\Ecoles\Models\Student;
use App\Modules\Education\Ecoles\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolYearController extends Controller
{
    public function index(Request $request, int $school): JsonResponse
    {
        $request->validate([
            'academic_year' => ['nullable', 'string', 'max:20'],
        ]);

        $schoolModel = School::query()->find($school);
        if (!$schoolModel) {
            return $this->errorResponse('École introuvable.', [], 404);
        }

        $selectedYear = $request->get('academic_year');
        $availableYears = $this->availableYears($school);

        if (!$selectedYear) {
            $selectedYear = $availableYears[0] ?? $this->currentAcademicYear();
        }

        $classQuery = SchoolClass::query()
            ->where('school_id', $school)
            ->where('academic_year', $selectedYear);

        $classIds = (clone $classQuery)->pluck('id');

        $summary = [
            'classes_total' => (clone $classQuery)->count(),
            'classes_archived' => (clone $classQuery)->whereNotNull('archived_at')->count(),
            'students_total' => Student::query()->where('school_id', $school)
                ->whereIn('class_id', $classIds)
                ->count(),
            'students_archived' => Student::query()->where('school_id', $school)
                ->whereIn('class_id', $classIds)
                ->whereNotNull('archived_at')
                ->count(),
            'teachers_total' => Teacher::query()->where('school_id', $school)
                ->whereHas('classes', function ($q) use ($selectedYear) {
                    $q->where('academic_year', $selectedYear);
                })
                ->count(),
            'schedules_total' => Schedule::query()->whereIn('class_id', $classIds)->count(),
            'grades_total' => Grade::query()->whereIn('class_id', $classIds)
                ->where('academic_year', $selectedYear)
                ->count(),
        ];

        return $this->successResponse([
            'school_id' => $school,
            'current_academic_year' => $this->currentAcademicYear(),
            'selected_academic_year' => $selectedYear,
            'available_years' => $availableYears,
            'summary' => $summary,
        ], 'Années scolaires récupérées.');
    }

    public function archive(Request $request, int $school): JsonResponse
    {
        $request->validate([
            'academic_year' => ['required', 'string', 'max:20'],
        ]);

        $academicYear = (string) $request->get('academic_year');

        $classIds = SchoolClass::query()
            ->where('school_id', $school)
            ->where('academic_year', $academicYear)
            ->whereNull('archived_at')
            ->pluck('id');

        if ($classIds->isEmpty()) {
            return $this->successResponse([
                'academic_year' => $academicYear,
                'archived' => ['classes' => 0, 'students' => 0, 'teachers' => 0, 'schedules' => 0],
            ], 'Aucun élément à archiver pour cette année.');
        }

        $now = now();
        $teacherIds = SchoolClass::query()->whereIn('id', $classIds)->pluck('teacher_id')->filter()->unique();

        $result = DB::transaction(function () use ($school, $academicYear, $classIds, $teacherIds, $now) {
            $classes = SchoolClass::query()->whereIn('id', $classIds)->update([
                'status' => 'archived',
                'archived_at' => $now,
            ]);

            $students = Student::query()
                ->where('school_id', $school)
                ->whereIn('class_id', $classIds)
                ->whereNull('archived_at')
                ->update([
                    'status' => 'archived',
                    'archived_at' => $now,
                ]);

            $schedules = Schedule::query()
                ->whereIn('class_id', $classIds)
                ->whereNull('archived_at')
                ->update([
                    'archived_at' => $now,
                ]);

            $teachers = 0;
            foreach ($teacherIds as $teacherId) {
                $hasActiveClasses = SchoolClass::query()
                    ->where('school_id', $school)
                    ->where('teacher_id', $teacherId)
                    ->whereNull('archived_at')
                    ->exists();

                if (!$hasActiveClasses) {
                    $teachers += Teacher::query()
                        ->where('school_id', $school)
                        ->where('id', $teacherId)
                        ->whereNull('archived_at')
                        ->update([
                            'status' => 'archived',
                            'archived_at' => $now,
                        ]);
                }
            }

            return [
                'classes' => $classes,
                'students' => $students,
                'teachers' => $teachers,
                'schedules' => $schedules,
            ];
        });

        return $this->successResponse([
            'academic_year' => $academicYear,
            'archived' => $result,
        ], 'Archivage de l\'année scolaire terminé.');
    }

    public function restore(Request $request, int $school): JsonResponse
    {
        $request->validate([
            'academic_year' => ['required', 'string', 'max:20'],
        ]);

        $academicYear = (string) $request->get('academic_year');

        $classIds = SchoolClass::query()
            ->where('school_id', $school)
            ->where('academic_year', $academicYear)
            ->whereNotNull('archived_at')
            ->pluck('id');

        if ($classIds->isEmpty()) {
            return $this->successResponse([
                'academic_year' => $academicYear,
                'restored' => ['classes' => 0, 'students' => 0, 'teachers' => 0, 'schedules' => 0],
            ], 'Aucun élément archivé à restaurer pour cette année.');
        }

        $teacherIds = SchoolClass::query()->whereIn('id', $classIds)->pluck('teacher_id')->filter()->unique();

        $result = DB::transaction(function () use ($school, $classIds, $teacherIds) {
            $classes = SchoolClass::query()->whereIn('id', $classIds)->update([
                'status' => 'active',
                'archived_at' => null,
            ]);

            $students = Student::query()
                ->where('school_id', $school)
                ->whereIn('class_id', $classIds)
                ->whereNotNull('archived_at')
                ->update([
                    'status' => 'active',
                    'archived_at' => null,
                ]);

            $schedules = Schedule::query()
                ->whereIn('class_id', $classIds)
                ->whereNotNull('archived_at')
                ->update([
                    'archived_at' => null,
                ]);

            $teachers = Teacher::query()
                ->where('school_id', $school)
                ->whereIn('id', $teacherIds)
                ->whereNotNull('archived_at')
                ->update([
                    'status' => 'active',
                    'archived_at' => null,
                ]);

            return [
                'classes' => $classes,
                'students' => $students,
                'teachers' => $teachers,
                'schedules' => $schedules,
            ];
        });

        return $this->successResponse([
            'academic_year' => $academicYear,
            'restored' => $result,
        ], 'Restauration de l\'année scolaire terminée.');
    }

    private function availableYears(int $schoolId): array
    {
        $classYears = SchoolClass::query()
            ->where('school_id', $schoolId)
            ->pluck('academic_year')
            ->filter()
            ->values();

        $gradeYears = Grade::query()
            ->whereHas('class_', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->pluck('academic_year')
            ->filter()
            ->values();

        return $classYears
            ->merge($gradeYears)
            ->push($this->currentAcademicYear())
            ->unique()
            ->sortDesc()
            ->values()
            ->all();
    }

    private function currentAcademicYear(): string
    {
        $now = now();
        $startYear = $now->month >= 8 ? $now->year : $now->year - 1;
        return $startYear . '-' . ($startYear + 1);
    }
}
