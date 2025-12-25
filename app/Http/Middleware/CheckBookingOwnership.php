<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Booking;

class CheckBookingOwnership
{
    public function handle(Request $request, Closure $next): Response
    {
        $booking = $request->route('booking');

        if ($booking instanceof Booking) {
            if (auth()->user()->id !== $booking->user_id && !auth()->user()->isAdmin()) {
                abort(403, 'You do not have permission to access this booking');
            }
        }

        return $next($request);
    }
}
