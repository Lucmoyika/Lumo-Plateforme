<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = 'fr';

        if ($request->user() && in_array($request->user()->locale ?? '', ['fr', 'en'])) {
            $locale = $request->user()->locale;
        } elseif ($request->hasHeader('Accept-Language')) {
            $acceptLang = substr($request->header('Accept-Language'), 0, 2);
            if (in_array($acceptLang, ['fr', 'en'])) {
                $locale = $acceptLang;
            }
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
