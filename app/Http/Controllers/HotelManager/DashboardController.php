<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'hotel.manager']);
    }

    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        $hotels = $user->managedHotels()->with('rooms')->get();

        $stats = [
            'total_hotels' => $hotels->count(),
            'total_rooms' => $hotels->sum(function($hotel) {
                return $hotel->rooms->count();
            }),
            'today_checkins' => Booking::whereIn('hotel_id', $hotels->pluck('id'))
                ->whereDate('check_in', Carbon::today())
                ->count(),
            'total_bookings' => Booking::whereIn('hotel_id', $hotels->pluck('id'))
                ->count(),
            'pending_payments' => Booking::whereIn('hotel_id', $hotels->pluck('id'))
                ->where('payment_status', 'unpaid')
                ->count(),
        ];

        $recentBookings = Booking::whereIn('hotel_id', $hotels->pluck('id'))
            ->with(['hotel', 'room', 'user'])
            ->latest()
            ->take(10)
            ->get();

        return view('hotel-manager.dashboard', compact('stats', 'recentBookings', 'hotels'));
    }
}