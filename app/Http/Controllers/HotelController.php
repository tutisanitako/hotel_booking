<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $query = Hotel::where('is_active', true)->with('rooms');

        // Search by location
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Filter by price
        if ($request->filled('min_price')) {
            $query->where('price_per_night', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_night', '<=', $request->max_price);
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }

        $hotels = $query->paginate(10);

        return view('hotels.index', compact('hotels'));
    }

    public function show($slug)
    {
        $hotel = Hotel::where('slug', $slug)
            ->where('is_active', true)
            ->with(['rooms.amenities', 'reviews' => function($query) {
                $query->where('is_approved', true)->latest()->take(5);
            }])
            ->firstOrFail();

        return view('hotels.show', compact('hotel'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'location' => 'nullable|string',
            'check_in' => 'nullable|date|after_or_equal:today',
            'check_out' => 'nullable|date|after:check_in',
            'adults' => 'nullable|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'rooms' => 'nullable|integer|min:1',
        ]);

        $query = Hotel::where('is_active', true);

        if ($request->filled('location')) {
            $query->where(function($q) use ($request) {
                $q->where('location', 'like', '%' . $request->location . '%')
                  ->orWhere('name', 'like', '%' . $request->location . '%')
                  ->orWhere('address', 'like', '%' . $request->location . '%');
            });
        }

        $hotels = $query->with('rooms')->paginate(10);

        // Use the same view as index
        return view('hotels.index', compact('hotels'));
    }
}