<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Modules\Education\Ecoles\Models\School;
use App\Modules\Education\Ecoles\Models\Student;
use App\Modules\Education\Ecoles\Models\Teacher;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSchoolScope
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $expectsJson = $request->expectsJson() || $request->is('api/*');

        if (!$user instanceof User) {
            if (!$expectsJson) {
                return redirect()->route('login');
            }

            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non authentifié.',
                'data' => null,
            ], 401);
        }

        $schoolId = (int) ($request->route('school') ?? 0);
        if ($schoolId <= 0) {
            return $next($request);
        }

        $school = School::query()->select(['id', 'director_id'])->find($schoolId);
        if (!$school) {
            if (!$expectsJson) {
                abort(404);
            }

            return response()->json([
                'success' => false,
                'message' => 'École introuvable.',
                'data' => null,
            ], 404);
        }

        // Super_admin/admin can access all schools (platform administration)
        if ($user->hasAnyRole(['super_admin', 'admin'])) {
            return $next($request);
        }

        if ((int) $school->director_id === (int) $user->id) {
            return $next($request);
        }

        if ($user->hasActiveSchoolDelegation($schoolId)) {
            return $next($request);
        }

        $isTeacherInSchool = Teacher::query()
            ->where('school_id', $schoolId)
            ->where('user_id', $user->id)
            ->exists();

        if ($isTeacherInSchool) {
            return $next($request);
        }

        $isStudentInSchool = Student::query()
            ->where('school_id', $schoolId)
            ->where('user_id', $user->id)
            ->exists();

        if ($isStudentInSchool) {
            return $next($request);
        }

        if (!$expectsJson) {
            return redirect()->route('schools.my')->with('error', 'Accès refusé à cet établissement.');
        }

        return response()->json([
            'success' => false,
            'message' => 'Accès refusé à cet établissement.',
            'data' => null,
        ], 403);
    }
}
