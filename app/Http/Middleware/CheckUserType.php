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
            if (!$user || $user->type !== $type) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'No autorizado'], 403);
                }
                
                return redirect()->route('home')
                    ->with('error', 'No tienes permiso para acceder a esta sección.');
            }

            return $next($request);
        } catch (\Exception $e) {
            Log::error('Error en CheckUserType: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Error del servidor'], 500);
            }
            return redirect()->route('home')
                ->with('error', 'Ha ocurrido un error. Por favor, intenta de nuevo.');
        }
    }
} 