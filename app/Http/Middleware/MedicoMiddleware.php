<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MedicoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            if (!Auth::check()) {
                Log::warning('Usuario no autenticado intentando acceder a ruta de médico');
                return redirect()->route('login');
            }

            $user = Auth::user();
            if ($user->type !== 'medico' || !$user->medico) {
                Log::warning('Usuario no médico o sin registro de médico intentando acceder', [
                    'user_id' => $user->id,
                    'type' => $user->type
                ]);
                return redirect()->route('login')
                    ->with('error', 'No tienes acceso a esta sección');
            }

            return $next($request);
        } catch (\Exception $e) {
            Log::error('Error en MedicoMiddleware: ' . $e->getMessage());
            return redirect()->route('login');
        }
    }
} 