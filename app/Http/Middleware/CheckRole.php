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
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Verificar el tipo de usuario y redirigir según corresponda
        if ($user->type !== $role) {
            if ($user->type === 'admin') {
                return redirect()->route('admin.medicos.index')
                    ->with('error', 'No tienes permiso para acceder a esa sección');
            } elseif ($user->type === 'medico') {
                return redirect()->route('medicos.dashboard')
                    ->with('error', 'No tienes permiso para acceder a esa sección');
            }
            return redirect()->route('login');
        }

        // Verificación adicional para médicos
        if ($role === 'medico' && !$user->medico) {
            \Log::error('Usuario médico sin registro en tabla medicos: ' . $user->id);
            return redirect()->route('login')
                ->with('error', 'Tu cuenta no está correctamente configurada');
        }

        return $next($request);
    }
} 