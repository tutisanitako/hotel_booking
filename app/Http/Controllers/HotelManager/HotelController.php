<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'hotel.manager']);
    }

    public function index()
    {
        $hotels = auth()->user()->managedHotels()
            ->withCount('rooms', 'bookings')
            ->get();

        return view('hotel-manager.hotels.index', compact('hotels'));
    }

    public function edit(Hotel $hotel)
    {
        // Check if user can manage this hotel
        if (!auth()->user()->canManageHotel($hotel->id)) {
            abort(403, 'You do not have permission to manage this hotel');
        }

        return view('hotel-manager.hotels.edit', compact('hotel'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        if (!auth()->user()->canManageHotel($hotel->id)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'main_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('main_image')) {
            if ($hotel->main_image) {
                Storage::disk('public')->delete($hotel->main_image);
            }
            $validated['main_image'] = $request->file('main_image')->store('hotels', 'public');
        }

        $hotel->update($validated);

        return redirect()->route('hotel-manager.hotels.index')
            ->with('success', 'Hotel updated successfully!');
    }

    public function rooms(Hotel $hotel)
    {
        if (!auth()->user()->canManageHotel($hotel->id)) {
            abort(403);
        }

        $rooms = $hotel->rooms()->with('amenities')->get();
        $amenities = Amenity::where('category', 'room')->get();

        return view('hotel-manager.hotels.rooms', compact('hotel', 'rooms', 'amenities'));
    }

    public function storeRoom(Request $request, Hotel $hotel)
    {
        if (!auth()->user()->canManageHotel($hotel->id)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'max_adults' => 'required|integer|min:1',
            'max_children' => 'required|integer|min:0',
            'available_rooms' => 'required|integer|min:1',
            'size_sqm' => 'nullable|numeric',
            'amenities' => 'nullable|array',
        ]);

        $room = $hotel->rooms()->create($validated);

        if ($request->has('amenities')) {
            $room->amenities()->sync($request->amenities);
        }

        return redirect()->route('hotel-manager.hotels.rooms', $hotel)
            ->with('success', 'Room added successfully!');
    }

    public function updateRoom(Request $request, Hotel $hotel, Room $room)
    {
        if (!auth()->user()->canManageHotel($hotel->id)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'max_adults' => 'required|integer|min:1',
            'max_children' => 'required|integer|min:0',
            'available_rooms' => 'required|integer|min:0',
            'size_sqm' => 'nullable|numeric',
            'amenities' => 'nullable|array',
        ]);

        $room->update($validated);

        if ($request->has('amenities')) {
            $room->amenities()->sync($request->amenities);
        }

        return redirect()->route('hotel-manager.hotels.rooms', $hotel)
            ->with('success', 'Room updated successfully!');
    }
}