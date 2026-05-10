<?php

namespace App\Modules\Education\Ecoles\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Education\Ecoles\Models\School;
use App\Modules\Education\Ecoles\Models\SchoolPermissionDelegation;
use App\Modules\Education\Ecoles\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchoolMemberPermissionController extends Controller
{
    private const DELEGABLE_PERMISSIONS = [
        'school-classes.view',
        'school-classes.create',
        'school-classes.update',
        'school-years.view',
        'school-years.archive',
        'students.view',
        'students.create',
        'students.update',
        'teachers.view',
        'teachers.create',
        'teachers.update',
        'attendance.view',
        'attendance.create',
        'grades.view',
        'grades.create',
        'grades.update',
        'tasks.view',
        'tasks.create',
        'tasks.update',
    ];

    public function index(Request $request, int $school): JsonResponse
    {
        $schoolModel = School::query()->find($school);
        if (!$schoolModel) {
            return $this->errorResponse('École introuvable.', [], 404);
        }

        $members = collect();

        if ($schoolModel->director_id) {
            $director = User::query()->find($schoolModel->director_id);
            if ($director) {
                $members->push($this->formatMember($director, 'director'));
            }
        }

        $teacherUsers = User::query()
            ->whereIn('id', Teacher::query()->where('school_id', $school)->pluck('user_id'))
            ->get();

        foreach ($teacherUsers as $teacherUser) {
            $members->push($this->formatMember($teacherUser, 'teacher'));
        }

        $delegations = SchoolPermissionDelegation::query()
            ->with(['user:id,name,email', 'grantor:id,name,email'])
            ->forSchool($school)
            ->latest()
            ->get()
            ->map(fn (SchoolPermissionDelegation $delegation) => [
                'id' => $delegation->id,
                'user' => $delegation->user ? [
                    'id' => $delegation->user->id,
                    'name' => $delegation->user->name,
                    'email' => $delegation->user->email,
                ] : null,
                'granted_by' => $delegation->grantor ? [
                    'id' => $delegation->grantor->id,
                    'name' => $delegation->grantor->name,
                    'email' => $delegation->grantor->email,
                ] : null,
                'role_name' => $delegation->role_name,
                'permissions' => $delegation->permissions,
                'starts_at' => optional($delegation->starts_at)?->toDateTimeString(),
                'ends_at' => optional($delegation->ends_at)?->toDateTimeString(),
                'notes' => $delegation->notes,
                'is_active' => $delegation->revoked_at === null && $delegation->starts_at <= now() && $delegation->ends_at >= now(),
            ]);

        return $this->successResponse([
            'school_id' => $school,
            'delegable_permissions' => self::DELEGABLE_PERMISSIONS,
            'members' => $members->unique('id')->values(),
            'delegations' => $delegations,
        ], 'Membres de l\'établissement récupérés.');
    }

    public function store(Request $request, int $school, int $user): JsonResponse
    {
        $schoolModel = School::query()->find($school);
        if (!$schoolModel) {
            return $this->errorResponse('École introuvable.', [], 404);
        }

        $actor = $request->user();
        if (!$actor) {
            return $this->errorResponse('Utilisateur non authentifié.', [], 401);
        }

        if ((int) $schoolModel->director_id !== (int) $actor->id) {
            return $this->errorResponse('Seul le gestionnaire de l\'école peut déléguer des droits.', [], 403);
        }

        $target = User::query()->find($user);
        if (!$target) {
            return $this->errorResponse('Utilisateur cible introuvable.', [], 404);
        }

        if ($target->hasAnyRole(['super_admin', 'admin'])) {
            return $this->errorResponse('La délégation directe sur ce compte est interdite.', [], 422);
        }

        $isTargetDirector = (int) $schoolModel->director_id === (int) $target->id;
        $isTargetTeacher = Teacher::query()
            ->where('school_id', $school)
            ->where('user_id', $target->id)
            ->exists();

        if (!$isTargetDirector && !$isTargetTeacher) {
            return $this->errorResponse('L\'utilisateur cible n\'est pas membre de cet établissement.', [], 422);
        }

        $validated = $request->validate([
            'role_name' => ['required', 'string', 'max:100'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['string'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $requested = collect($validated['permissions'])->unique()->values();
        $forbidden = $requested->diff(self::DELEGABLE_PERMISSIONS)->values();

        if ($forbidden->isNotEmpty()) {
            return $this->errorResponse('Certaines permissions ne peuvent pas être déléguées.', [
                'forbidden_permissions' => $forbidden,
                'allowed_permissions' => self::DELEGABLE_PERMISSIONS,
            ], 422);
        }

        $delegation = SchoolPermissionDelegation::query()->create([
            'school_id' => $school,
            'user_id' => $target->id,
            'granted_by' => $actor->id,
            'role_name' => $validated['role_name'],
            'permissions' => $requested->values()->all(),
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return $this->successResponse([
            'user_id' => $target->id,
            'school_id' => $school,
            'delegation' => [
                'id' => $delegation->id,
                'role_name' => $delegation->role_name,
                'permissions' => $delegation->permissions,
                'starts_at' => $delegation->starts_at?->toDateTimeString(),
                'ends_at' => $delegation->ends_at?->toDateTimeString(),
            ],
        ], 'Droits délégués mis à jour.');
    }

    public function revoke(Request $request, int $school, int $user, int $delegation): JsonResponse
    {
        $schoolModel = School::query()->find($school);
        if (!$schoolModel) {
            return $this->errorResponse('École introuvable.', [], 404);
        }

        $actor = $request->user();
        if (!$actor) {
            return $this->errorResponse('Utilisateur non authentifié.', [], 401);
        }

        if ((int) $schoolModel->director_id !== (int) $actor->id) {
            return $this->errorResponse('Seul le gestionnaire de l\'école peut révoquer des droits.', [], 403);
        }

        $record = SchoolPermissionDelegation::query()
            ->forSchool($school)
            ->where('user_id', $user)
            ->find($delegation);

        if (!$record) {
            return $this->errorResponse('Délégation introuvable.', [], 404);
        }

        $record->update(['revoked_at' => now()]);

        return $this->successResponse([
            'id' => $record->id,
            'revoked_at' => $record->revoked_at?->toDateTimeString(),
        ], 'Droits révoqués.');
    }

    public function sync(Request $request, int $school, int $user): JsonResponse
    {
        return $this->store($request, $school, $user);
    }

    private function formatMember(User $user, string $memberType): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'member_type' => $memberType,
            'delegated_permissions' => $user->activeSchoolPermissions((int) request()->route('school'))
                ->intersect(self::DELEGABLE_PERMISSIONS)
                ->values(),
        ];
    }
}
