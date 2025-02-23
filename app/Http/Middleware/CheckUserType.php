<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserType
{
    public function handle(Request $request, Closure $next, $type)
    {
        if ($request->user()->type !== $type) {
            if ($request->user()->type === 'admin') {
                return redirect()->route('admin.medicos.index');
            }
            return redirect()->route('medico.dashboard');
        }

        return $next($request);
    }
} 