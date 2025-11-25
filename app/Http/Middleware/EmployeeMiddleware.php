<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EmployeeMiddleware
{
    public function handle(Request $request, Closure $next)
{
    if (auth()->check() && in_array(auth()->user()->role, ['fleet_assistant', 'booking_officer'])) {
        return $next($request);
    }

    abort(403, 'Unauthorized');
}



}
