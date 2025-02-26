<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckLastActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Verificar última actividad
            if (session('last_activity')) {
                $lastActivity = Carbon::parse(session('last_activity'));
                $now = Carbon::now();
                
                // Si han pasado más de 3 minutos
                if ($lastActivity->diffInMinutes($now) >= 3) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('login')->with('warning', 'Su sesión ha expirado por inactividad.');
                }
            }
            
            // Actualizar timestamp de última actividad
            session(['last_activity' => Carbon::now()]);
        }
        
        return $next($request);
    }
}
