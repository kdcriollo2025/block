<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserType
{
    public function handle(Request $request, Closure $next, $type)
    {
        $user = $request->user();
        
        if (!$user || $user->type !== $type) {
            if ($user && $user->type === 'admin') {
                return redirect()->route('admin.medicos.index');
            } elseif ($user && $user->type === 'medico') {
                return redirect()->route('medico.dashboard');
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
} 