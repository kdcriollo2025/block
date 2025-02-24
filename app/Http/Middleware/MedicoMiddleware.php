<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado y es médico
        if (!Auth::check() || Auth::user()->type !== 'medico') {
            return redirect()->route('login');
        }

        // Verificar si tiene registro en la tabla médicos
        if (!Auth::user()->medico) {
            return redirect()->route('login')
                ->with('error', 'Cuenta de médico no configurada correctamente');
        }

        return $next($request);
    }
} 