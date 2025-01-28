<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckActiveUser
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        
        if ($user->type === 'medico') {
            $medico = $user->medico;
            if (!$medico || !$medico->is_active) {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Tu cuenta ha sido desactivada. Por favor, contacta al administrador.');
            }
        }

        // Verificar primer ingreso
        if ($user->first_login && !$request->routeIs('password.change*')) {
            return redirect()->route('password.change.form')
                ->with('warning', 'Por favor, cambia tu contraseña antes de continuar.');
        }

        return $next($request);
    }
} 