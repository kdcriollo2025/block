<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MedicoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            Log::warning('Usuario no autenticado intentando acceder a ruta de médico');
            return redirect()->route('login');
        }

        if (auth()->user()->type !== 'medico') {
            Log::warning('Usuario no médico intentando acceder a ruta de médico');
            return redirect()->route('login');
        }

        return $next($request);
    }
} 