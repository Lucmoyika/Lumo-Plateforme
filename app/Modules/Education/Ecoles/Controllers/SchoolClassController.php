<?php

namespace App\Modules\Education\Ecoles\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\Requests\SchoolClassRequest;
use App\Modules\Education\Ecoles\Services\SchoolClassService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function __construct(private readonly SchoolClassService $classService) {}

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'academic_year' => ['nullable', 'string', 'max:20'],
            'include_archived' => ['nullable', 'boolean'],
        ]);

        $schoolId = (int) ($request->route('school') ?? $request->get('school_id') ?? 0);
        $academicYear = $request->get('academic_year');
        $includeArchived = $request->boolean('include_archived', false);

        if ($schoolId) {
            $classes = $this->classService->getBySchool($schoolId, $academicYear, $includeArchived);
            return $this->successResponse($classes, 'Classes récupérées.');
        }

        $paginator = $this->classService->paginate((int) $request->get('per_page', 15));
        return $this->paginatedResponse($paginator, 'Classes récupérées.');
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $class = $this->classService->getById($id, ['school', 'teacher', 'students']);

        if (!$class) {
            return $this->errorResponse('Classe introuvable.', [], 404);
        }

        $schoolId = (int) ($request->route('school') ?? 0);
        if ($schoolId > 0 && (int) $class->school_id !== $schoolId) {
            return $this->errorResponse('Classe hors périmètre établissement.', [], 403);
        }

        return $this->successResponse($class, 'Classe récupérée.');
    }

    public function store(SchoolClassRequest $request): JsonResponse
    {
        $schoolId = (int) ($request->route('school') ?? $request->get('school_id') ?? 0);

        $data = $request->validated();

        if ($schoolId > 0) {
            $data['school_id'] = $schoolId;
        }

        $class = $this->classService->create($data);

        return $this->successResponse($class, 'Classe créée.', 201);
    }

    public function update(SchoolClassRequest $request, int $id): JsonResponse
    {
        $existing = $this->classService->getById($id);
        if (!$existing) {
            return $this->errorResponse('Classe introuvable.', [], 404);
        }

        $schoolId = (int) ($request->route('school') ?? 0);
        if ($schoolId > 0 && (int) $existing->school_id !== $schoolId) {
            return $this->errorResponse('Classe hors périmètre établissement.', [], 403);
        }

        $data = $request->validated();

        $class = $this->classService->update($id, $data);

        return $this->successResponse($class, 'Classe mise à jour.');
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $existing = $this->classService->getById($id);
        if (!$existing) {
            return $this->errorResponse('Classe introuvable.', [], 404);
        }

        $schoolId = (int) ($request->route('school') ?? 0);
        if ($schoolId > 0 && (int) $existing->school_id !== $schoolId) {
            return $this->errorResponse('Classe hors périmètre établissement.', [], 403);
        }

        $this->classService->delete($id);

        return $this->successResponse(null, 'Classe supprimée.');
    }

    /**
     * Manage schedule for a class.
     */
    public function updateSchedule(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'schedules'            => ['required', 'array'],
            'schedules.*.day'      => ['required', 'string', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'schedules.*.start'    => ['required', 'date_format:H:i'],
            'schedules.*.end'      => ['required', 'date_format:H:i', 'after:schedules.*.start'],
            'schedules.*.subject'  => ['required', 'string', 'max:100'],
            'schedules.*.teacher_id' => ['nullable', 'exists:users,id'],
            'schedules.*.room' => ['nullable', 'string', 'max:100'],
            'schedules.*.color' => ['nullable', 'string', 'max:20'],
        ]);

        $class = $this->classService->getById($id);
        if (!$class) {
            return $this->errorResponse('Classe introuvable.', [], 404);
        }

        $schoolId = (int) ($request->route('school') ?? 0);
        if ($schoolId > 0 && (int) $class->school_id !== $schoolId) {
            return $this->errorResponse('Classe hors périmètre établissement.', [], 403);
        }

        $class->schedules()->delete();
        $dayMap = [
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6,
            'sunday' => 7,
        ];

        foreach ($data['schedules'] as $schedule) {
            $class->schedules()->create([
                'subject' => $schedule['subject'],
                'teacher_id' => $schedule['teacher_id'] ?? null,
                'day_of_week' => $dayMap[$schedule['day']],
                'start_time' => $schedule['start'],
                'end_time' => $schedule['end'],
                'room' => $schedule['room'] ?? null,
                'color' => $schedule['color'] ?? null,
            ]);
        }

        return $this->successResponse($class->load('schedules'), 'Emploi du temps mis à jour.');
    }

    public function getSchedule(Request $request, int $id): JsonResponse
    {
        $class = $this->classService->getById($id, ['schedules.teacher']);
        if (!$class) {
            return $this->errorResponse('Classe introuvable.', [], 404);
        }

        $schoolId = (int) ($request->route('school') ?? 0);
        if ($schoolId > 0 && (int) $class->school_id !== $schoolId) {
            return $this->errorResponse('Classe hors périmètre établissement.', [], 403);
        }

        $schedules = $class->schedules()
            ->whereNull('archived_at')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return $this->successResponse($schedules, 'Emploi du temps récupéré.');
    }
}
