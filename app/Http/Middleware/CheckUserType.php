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
            
            // Log para debugging
            Log::info('CheckUserType middleware', [
                'required_type' => $type,
                'user_type' => $user->type,
                'user_id' => $user->id
            ]);

            if (!$user || $user->type !== $type) {
                Log::warning('Acceso no autorizado', [
                    'user_id' => $user->id ?? 'no auth',
                    'user_type' => $user->type ?? 'no type',
                    'required_type' => $type
                ]);
                
                return redirect()->route('home')->with('error', 'Acceso no autorizado');
            }

            return $next($request);
            
        } catch (\Exception $e) {
            Log::error('Error en middleware CheckUserType', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('home')->with('error', 'Error de autenticación');
        }
    }
} 