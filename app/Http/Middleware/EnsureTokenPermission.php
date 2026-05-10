<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenPermission
{
    /**
     * Ensure user is authenticated and has one of the required permissions.
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (!$user instanceof User) {
            $plainToken = $request->cookie('lumo_token') ?: $request->bearerToken();

            if (!$plainToken) {
                return redirect()->route('login');
            }

            $accessToken = PersonalAccessToken::findToken($plainToken);

            if (!$accessToken) {
                return redirect()->route('login')->withCookie(cookie()->forget('lumo_token'));
            }

            $user = $accessToken->tokenable;
        }

        if (!$user instanceof User || $user->status !== 'active') {
            return redirect()->route('login')->withCookie(cookie()->forget('lumo_token'));
        }

        $schoolId = (int) ($request->route('school') ?? 0);
        $hasPermission = !empty($permissions) && $user->canAny($permissions);

        if (!$hasPermission && $schoolId > 0 && !empty($permissions)) {
            $hasPermission = $user->activeSchoolPermissions($schoolId)->intersect($permissions)->isNotEmpty();
        }

        if (!empty($permissions) && !$hasPermission) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès refusé.',
                    'data' => null,
                ], 403);
            }

            return redirect('/dashboard')->with('error', 'Accès refusé pour votre compte.');
        }

        Auth::setUser($user);
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
