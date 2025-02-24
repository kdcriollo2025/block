<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MedicoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->type === 'medico') {
            return $next($request);
        }
        
        return redirect()->route('login');
    }
} 