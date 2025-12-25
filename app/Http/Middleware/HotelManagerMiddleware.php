<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HotelManagerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->isHotelManager() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access. Only hotel managers can access this area.');
        }

        return $next($request);
    }
}