<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ActivityLog;

class LogActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user() && in_array($request->method(), ['POST', 'PUT', 'DELETE'])) {
            ActivityLog::log(
                $request->route()->getName() ?? $request->path(),
                null,
                $request->user(),
                ['method' => $request->method(), 'url' => $request->url()]
            );
        }

        return $response;
    }
}
