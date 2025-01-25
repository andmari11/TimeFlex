<?php

namespace App\Http\Middleware;

use App\Http\Controllers\BrowserHistoryController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HistorialAccesosMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        BrowserHistoryController::add(
            $request->route()->getName() ?? 'Sin nombre', $request->url()
        );

        return $next($request);
    }
}
