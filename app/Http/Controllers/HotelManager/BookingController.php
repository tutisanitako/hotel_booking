<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'hotel.manager']);
    }

    public function index(Request $request)
    {
        // gets the logged-in manager's ID
        /** @var User $user */
        $user = auth()->user();
        
        // finds which hotels they manage
        $hotels = $user->managedHotels()->pluck('hotels.id');

        $query = Booking::whereIn('hotel_id', $hotels)
            ->with(['hotel', 'room', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $bookings = $query->latest()->paginate(20);

        return view('hotel-manager.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        /** @var User $user */
        $user = auth()->user();
        $hotels = $user->managedHotels()->pluck('hotels.id');
        
        if (!$hotels->contains($booking->hotel_id)) {
            abort(403, 'You do not have permission to view this booking');
        }

        $booking->load(['hotel', 'room', 'user']);

        return view('hotel-manager.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        /** @var User $user */
        $user = auth()->user();
        $hotels = $user->managedHotels()->pluck('hotels.id');
        
        if (!$hotels->contains($booking->hotel_id)) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $booking->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Booking status updated successfully!');
    }

    public function updatePaymentStatus(Request $request, Booking $booking)
    {
        /** @var User $user */
        $user = auth()->user();
        $hotels = $user->managedHotels()->pluck('hotels.id');
        
        if (!$hotels->contains($booking->hotel_id)) {
            abort(403);
        }

        $request->validate([
            'payment_status' => 'required|in:unpaid,paid,refunded',
        ]);

        $booking->update(['payment_status' => $request->payment_status]);

        return redirect()->back()
            ->with('success', 'Payment status updated successfully!');
    }
}