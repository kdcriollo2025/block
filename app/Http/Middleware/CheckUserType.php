<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckUserType
{
    public function handle(Request $request, Closure $next, $type)
    {
        try {
            if (!auth()->check()) {
                return redirect()->route('login');
            }

            $user = auth()->user();
            
            if ($user->type !== $type) {
                return redirect()->route('home')
                    ->with('error', 'No tienes permiso para acceder a esta sección.');
            }

            if ($type === 'medico' && !$user->medico) {
                Log::error('Usuario médico sin perfil de médico: ' . $user->id);
                return redirect()->route('home')
                    ->with('error', 'No se encontró el perfil de médico.');
            }

            return $next($request);
        } catch (\Exception $e) {
            Log::error('Error en CheckUserType: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->route('home')
                ->with('error', 'Ha ocurrido un error. Por favor, intenta de nuevo.');
        }
    }
} 