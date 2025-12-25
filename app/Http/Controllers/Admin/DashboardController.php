<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Booking;
use App\Models\User;
use App\Models\Review;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $stats = [
            'total_hotels' => Hotel::count(),
            'total_bookings' => Booking::count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_revenue' => Booking::where('payment_status', 'paid')->sum('total_price'),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'active_bookings' => Booking::where('status', 'confirmed')
                ->where('check_in', '<=', Carbon::now())
                ->where('check_out', '>=', Carbon::now())
                ->count(),
            'pending_reviews' => Review::where('is_approved', false)->count(),
        ];

        $recentBookings = Booking::with(['hotel', 'user'])
            ->latest()
            ->take(10)
            ->get();

        $topHotels = Hotel::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'topHotels'));
    }
}