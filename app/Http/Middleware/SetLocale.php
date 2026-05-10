<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['fr', 'en'];
        $defaultLocale = 'fr';

        if ($request->filled('lang')) {
            $lang = (string) $request->query('lang');
            if (in_array($lang, $supportedLocales, true)) {
                session(['locale' => $lang]);
            }
        }

        $locale = (string) session('locale', '');
        if (!in_array($locale, $supportedLocales, true)) {
            $locale = '';
        }

        if (!$locale && $request->user() && in_array($request->user()->locale ?? '', $supportedLocales, true)) {
            $locale = $request->user()->locale;
            session(['locale' => $locale]);
        }

        if (!$locale) {
            $plainToken = $request->cookie('lumo_token') ?: $request->bearerToken();
            if ($plainToken) {
                $accessToken = PersonalAccessToken::findToken($plainToken);
                $tokenUser = $accessToken?->tokenable;
                if ($tokenUser instanceof User && in_array($tokenUser->locale ?? '', $supportedLocales, true)) {
                    $locale = $tokenUser->locale;
                    session(['locale' => $locale]);
                }
            }
        }

        if (!$locale && $request->hasHeader('Accept-Language')) {
            $acceptLang = substr($request->header('Accept-Language'), 0, 2);
            if (in_array($acceptLang, $supportedLocales, true)) {
                $locale = $acceptLang;
                session(['locale' => $locale]);
            }
        }

        if (!$locale) {
            $locale = $defaultLocale;
            session(['locale' => $locale]);
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
