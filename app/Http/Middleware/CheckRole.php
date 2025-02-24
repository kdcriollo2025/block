<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        if (auth()->user()->type === $role) {
            if ($role === 'medico' && !auth()->user()->medico) {
                \Log::error('Usuario médico sin registro en tabla medicos: ' . auth()->id());
                return redirect('/')->with('error', 'Tu cuenta no está correctamente configurada');
            }
            return $next($request);
        }

        return redirect('/')->with('error', 'No tienes permiso para acceder a esta área');
    }
} 