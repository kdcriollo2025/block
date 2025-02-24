<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user() || !in_array($request->user()->type, $roles)) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Unauthorized.'], 403);
            }
            return redirect()->route('login')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
} 