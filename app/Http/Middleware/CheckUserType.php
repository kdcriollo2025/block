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
            $user = $request->user();

            if (!$user) {
                Log::warning('Usuario no autenticado intentando acceder a ruta protegida');
                return redirect()->route('login');
            }

            Log::info('Verificando tipo de usuario', [
                'user_id' => $user->id,
                'current_type' => $user->type,
                'required_type' => $type,
                'route' => $request->route()->getName()
            ]);

            if ($user->type !== $type) {
                Log::warning('Usuario con tipo incorrecto intentando acceder', [
                    'user_id' => $user->id,
                    'current_type' => $user->type,
                    'required_type' => $type
                ]);

                if ($request->expectsJson()) {
                    return response()->json(['error' => 'No autorizado'], 403);
                }

                return redirect()
                    ->route('home')
                    ->with('error', 'No tiene permiso para acceder a esta sección');
            }

            return $next($request);

        } catch (\Exception $e) {
            Log::error('Error en middleware CheckUserType', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Error de autenticación'], 500);
            }

            return redirect()
                ->route('home')
                ->with('error', 'Error de autenticación');
        }
    }
} 