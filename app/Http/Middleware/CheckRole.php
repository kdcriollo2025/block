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
        if (auth()->check() && auth()->user()->role === $role) {
            return $next($request);
        }
        
        // Si es médico, redirigir a su dashboard específico
        if ($role === 'medico' && auth()->user()->role === 'medico') {
            return redirect()->route('medico.dashboard');
        }

        return redirect('/')->with('error', 'No tienes permiso para acceder a esta área');
    }
} 