<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserType
{
    public function handle(Request $request, Closure $next, $type)
    {
        if (!auth()->check() || auth()->user()->type !== $type) {
            return redirect()->route('home')
                ->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
} 