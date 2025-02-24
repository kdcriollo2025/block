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
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        foreach($roles as $role) {
            if($user->type === $role) {
                // Si es médico, verificar que tenga registro en la tabla medicos
                if ($role === 'medico') {
                    if (!$user->medico) {
                        \Log::error('Usuario médico sin registro en tabla medicos: ' . $user->id);
                        return redirect()->route('login')
                            ->with('error', 'Tu cuenta no está correctamente configurada');
                    }
                }
                return $next($request);
            }
        }

        // Redirigir según el tipo de usuario
        if ($user->type === 'admin') {
            return redirect()->route('admin.medicos.index');
        }
        
        if ($user->type === 'medico') {
            return redirect()->route('medicos.dashboard');
        }

        abort(403, 'Unauthorized action.');
    }
} 