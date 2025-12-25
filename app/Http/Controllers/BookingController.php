<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request, string $hotelSlug)
    {
        $hotel = Hotel::where('slug', $hotelSlug)->firstOrFail();
        
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'rooms_count' => 'required|integer|min:1',
        ]);

        $room = Room::findOrFail($request->room_id);
        
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $nights = $checkIn->diffInDays($checkOut);

        $originalPrice = $room->price_per_night * $nights * $request->rooms_count;
        $discount = $originalPrice * 0.04;
        $totalPrice = $originalPrice - $discount;

        return view('bookings.create', compact('hotel', 'room', 'checkIn', 'checkOut', 'nights', 'originalPrice', 'discount', 'totalPrice', 'request'));
    }

    public function store(Request $request)
    {
        // Conditional validation based on payment timing
        $rules = [
            'hotel_id' => 'required|exists:hotels,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'rooms_count' => 'required|integer|min:1',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'country' => 'nullable|string',
            'estimated_arrival' => 'nullable|string',
            'special_requests' => 'nullable|string',
            'payment_timing' => 'required|in:now,later',
        ];

        // Only require payment fields if paying now
        if ($request->payment_timing === 'now') {
            $rules['payment_method'] = 'required|string';
            $rules['card_name'] = 'required|string';
            $rules['card_number'] = 'required|string';
            $rules['exp_date'] = 'required|string';
            $rules['cvc'] = 'required|string';
        }

        $validated = $request->validate($rules);
        $room = Room::findOrFail($validated['room_id']);
        
        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $nights = $checkIn->diffInDays($checkOut);

        $originalPrice = $room->price_per_night * $nights * $validated['rooms_count'];
        $discount = $originalPrice * 0.04;
        $totalPrice = $originalPrice - $discount;

        // Determine payment status based on timing
        $paymentStatus = $validated['payment_timing'] === 'now' ? 'paid' : 'unpaid';

        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'user_id' => auth()->id(),
            'hotel_id' => $validated['hotel_id'],
            'room_id' => $validated['room_id'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'nights' => $nights,
            'adults' => $validated['adults'],
            'children' => $validated['children'] ?? 0,
            'rooms_count' => $validated['rooms_count'],
            'original_price' => $originalPrice,
            'discount' => $discount,
            'total_price' => $totalPrice,
            'status' => 'confirmed',
            'payment_status' => $paymentStatus,
            'payment_method' => $validated['payment_method'] ?? 'pay_at_hotel',
            'special_requests' => $validated['special_requests'] ?? null,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'country' => $validated['country'] ?? null,
            'estimated_arrival' => $validated['estimated_arrival'] ?? null,
        ]);

        // Generate booking confirmation file
        $this->generateBookingConfirmation($booking);

        return redirect()->route('bookings.confirmation', $booking->booking_number)
            ->with('success', 'Booking confirmed successfully!');
    }

    public function confirmation($bookingNumber)
    {
        $booking = Booking::where('booking_number', $bookingNumber)
            ->with(['hotel', 'room', 'user'])
            ->firstOrFail();

        return view('bookings.confirmation', compact('booking'));
    }

    public function myBookings()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->with(['hotel', 'room'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.my-bookings', compact('bookings'));
    }

    private function generateBookingConfirmation(Booking $booking)
    {
        $bookingNumber = $booking->booking_number;
        $hotelName = $booking->hotel->name;
        $roomName = $booking->room->name;
        $checkIn = $booking->check_in->format('Y-m-d');
        $checkOut = $booking->check_out->format('Y-m-d');
        $nights = $booking->nights;
        $firstName = $booking->first_name;
        $lastName = $booking->last_name;
        $totalPrice = $booking->total_price;
        $paymentStatus = ucfirst($booking->payment_status);
        
        $content = <<<EOT
BOOKING CONFIRMATION

Booking Number: {$bookingNumber}
Hotel: {$hotelName}
Room: {$roomName}
Check-in: {$checkIn}
Check-out: {$checkOut}
Nights: {$nights}
Guest: {$firstName} {$lastName}
Total Price: \${$totalPrice}
Payment Status: {$paymentStatus}

Thank you for booking with EasySet24!
EOT;

        Storage::put("bookings/{$bookingNumber}.txt", $content);
    }
}