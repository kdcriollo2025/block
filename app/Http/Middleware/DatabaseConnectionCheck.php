<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseConnectionCheck
{
    public function handle($request, Closure $next)
    {
        try {
            DB::connection()->getPdo();
            Log::info('Database connection successful');
        } catch (\Exception $e) {
            Log::error('Database connection failed: ' . $e->getMessage());
            return response('Database connection failed.', 500);
        }

        return $next($request);
    }
} 