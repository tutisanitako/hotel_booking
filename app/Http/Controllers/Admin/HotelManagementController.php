<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class HotelManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $hotels = Hotel::withCount('rooms', 'bookings')
            ->latest()
            ->paginate(15);

        return view('admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        return view('admin.hotels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'address' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'rating' => 'nullable|integer|min:0|max:10',
            'main_image' => 'nullable|image|max:2048',
            'breakfast_included' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('hotels', 'public');
        }

        Hotel::create($data);

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel created successfully!');
    }

    public function edit(Hotel $hotel)
    {
        return view('admin.hotels.edit', compact('hotel'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'address' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'rating' => 'nullable|integer|min:0|max:10',
            'main_image' => 'nullable|image|max:2048',
            'breakfast_included' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('main_image')) {
            if ($hotel->main_image) {
                Storage::disk('public')->delete($hotel->main_image);
            }
            $data['main_image'] = $request->file('main_image')->store('hotels', 'public');
        }

        $hotel->update($data);

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel updated successfully!');
    }

    public function destroy(Hotel $hotel)
    {
        if ($hotel->main_image) {
            Storage::disk('public')->delete($hotel->main_image);
        }
        
        $hotel->delete();

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel deleted successfully!');
    }

    // Room Management
    public function rooms(Hotel $hotel)
    {
        $rooms = $hotel->rooms()->with('amenities')->get();
        $amenities = Amenity::where('category', 'room')->get();

        return view('admin.hotels.rooms', compact('hotel', 'rooms', 'amenities'));
    }

    public function storeRoom(Request $request, Hotel $hotel)
    {
        $request->validate([
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

        $room = $hotel->rooms()->create($request->except('amenities'));

        if ($request->has('amenities')) {
            $room->amenities()->sync($request->amenities);
        }

        return redirect()->route('admin.hotels.rooms', $hotel)
            ->with('success', 'Room created successfully!');
    }

    public function updateRoom(Request $request, Hotel $hotel, Room $room)
    {
        $request->validate([
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

        $room->update($request->except('amenities'));

        if ($request->has('amenities')) {
            $room->amenities()->sync($request->amenities);
        }

        return redirect()->route('admin.hotels.rooms', $hotel)
            ->with('success', 'Room updated successfully!');
    }

    public function destroyRoom(Hotel $hotel, Room $room)
    {
        $room->delete();

        return redirect()->route('admin.hotels.rooms', $hotel)
            ->with('success', 'Room deleted successfully!');
    }
}