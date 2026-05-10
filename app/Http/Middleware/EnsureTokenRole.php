<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenRole
{
    /**
     * Ensure user is authenticated via Sanctum token cookie and optionally has one of the expected roles.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
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

        if (!empty($roles) && !in_array($user->role, $roles, true)) {
            return redirect('/dashboard')->with('error', 'Accès refusé pour votre rôle.');
        }

        Auth::setUser($user);
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
