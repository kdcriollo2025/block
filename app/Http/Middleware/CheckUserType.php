<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserType
{
    public function handle(Request $request, Closure $next, $type)
    {
        if ($request->user()->type !== $type) {
            return redirect()->route('home')->with('error', 'Acceso no autorizado');
        }

        return $next($request);
    }
} 