<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Modules\Core\Models\AuditLog;

class AuditLogMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            AuditLog::create([
                'user_id'    => $request->user()->id,
                'action'     => $request->method() . ' ' . $request->path(),
                'model_type' => null,
                'model_id'   => null,
                'new_values' => $request->except(['password', 'password_confirmation', 'token']),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }
}
