<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredHotels = Hotel::where('is_featured', true)
            ->where('is_active', true)
            ->take(6)
            ->get();

        $popularDestinations = Hotel::where('is_active', true)
            ->withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(12)
            ->get();

        return view('home', compact('featuredHotels', 'popularDestinations'));
    }

    public function about()
    {
        return view('about');
    }
}