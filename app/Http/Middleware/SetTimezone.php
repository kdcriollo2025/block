<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;

class SetTimezone
{
    public function handle($request, Closure $next)
    {
        date_default_timezone_set('America/Guayaquil');
        Carbon::setLocale('es');
        
        return $next($request);
    }
} 