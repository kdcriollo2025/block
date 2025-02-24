<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    public function handle(Request $request, Closure $next, $type)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        if ($user->type !== $type) {
            if ($user->type === 'admin') {
                return redirect()->route('admin.medicos.index');
            } elseif ($user->type === 'medico') {
                return redirect()->route('medicos.index');
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
} 