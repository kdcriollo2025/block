<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class DebugMiddleware
{
    public function handle($request, Closure $next)
    {
        Log::info('Request URL: ' . $request->url());
        Log::info('User: ', [
            'authenticated' => auth()->check(),
            'type' => auth()->check() ? auth()->user()->type : 'guest'
        ]);
        
        return $next($request);
    }
} 