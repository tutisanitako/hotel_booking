@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<div class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 mb-4">Discover Your Trip Worldwide!</h1>
        
        <!-- Search Form -->
        <div class="card p-4 mt-5">
            <form action="{{ route('hotels.search') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-start d-block">Where are you going?</label>
                        <input type="text" name="location" class="form-control" placeholder="Enter destination" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Check-in</label>
                        <input type="date" name="check_in" class="form-control" 
                               value="{{ date('Y-m-d') }}" 
                               min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Check-out</label>
                        <input type="date" name="check_out" class="form-control" 
                               value="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Guests</label>
                        <select name="adults" class="form-select">
                            <option value="1">1 Adult</option>
                            <option value="2" selected>2 Adults</option>
                            <option value="3">3 Adults</option>
                            <option value="4">4 Adults</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Rooms</label>
                        <input type="number" name="rooms_count" class="form-control" value="1" min="1" max="5">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Special Offers -->
<div class="container my-5">
    <h2 class="mb-4">Special Offers</h2>
    <div class="row g-4">
        @foreach($featuredHotels->take(6) as $hotel)
        <div class="col-md-4">
            <div class="card h-100">
                @if($hotel->main_image)
                    <img src="{{ asset('storage/' . $hotel->main_image) }}" 
                         class="card-img-top" alt="{{ $hotel->name }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-hotel fa-4x text-white"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $hotel->name }}</h5>
                    <p class="card-text text-muted">
                        <i class="fas fa-map-marker-alt"></i> {{ $hotel->location }}
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if($hotel->rating > 0)
                                <span class="badge bg-primary">{{ $hotel->rating }}/10</span>
                            @endif
                            @if($hotel->breakfast_included)
                                <small class="text-success"><i class="fas fa-check"></i> Breakfast</small>
                            @endif
                        </div>
                        <div class="text-end">
                            <h5 class="text-primary mb-0">${{ number_format($hotel->price_per_night, 0) }}</h5>
                            <small class="text-muted">per night</small>
                        </div>
                    </div>
                    <a href="{{ route('hotels.show', $hotel->slug) }}" class="btn btn-outline-primary w-100 mt-3">
                        Check Availability <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Explore Stays -->
<div class="container my-5">
    <h2 class="mb-4">Explore Stays in Trending Destinations</h2>
    <p class="text-muted mb-4">Find Hot Stays!</p>
    <div class="row g-4">
        @foreach($popularDestinations->take(12) as $hotel)
        <div class="col-md-3">
            <div class="card h-100">
                @if($hotel->main_image)
                    <img src="{{ asset('storage/' . $hotel->main_image) }}" 
                         class="card-img-top" alt="{{ $hotel->location }}" style="height: 150px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 150px;">
                        <i class="fas fa-map-marked-alt fa-3x text-white"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h6 class="card-title">{{ $hotel->location }}</h6>
                    <p class="card-text small text-muted mb-1">{{ $hotel->name }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">From ${{ number_format($hotel->price_per_night, 0) }}</small>
                        @if($hotel->rating > 0)
                            <div>
                                @for($i = 0; $i < min($hotel->rating, 5); $i++)
                                    <i class="fas fa-star text-warning"></i>
                                @endfor
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Get Inspirations -->
<div class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <div class="bg-primary text-white p-5 rounded" style="min-height: 400px; display: flex; align-items: center; justify-content: center;">
                <div class="text-center">
                    <i class="fas fa-mountain fa-5x mb-3"></i>
                    <h3>Beautiful Destinations Await</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h2>Get Inspirations For Your Next Trip</h2>
            <p class="text-muted">Read About Wonderful Adventures</p>
            <h3 class="mt-4">Difficult Roads Lead To Beautiful Destination.</h3>
            <a href="{{ route('about') }}" class="btn btn-primary mt-3">Read More <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</div>

<!-- App Download Section -->
<div class="container my-5">
    <div class="card bg-light">
        <div class="card-body p-5">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3>Go Further With The EasySet24 App</h3>
                    <p class="text-muted">Easy Savings On Chosen Hotels And Flights When You Book Through The EasySet24 Website. 
                    Additionally, Earn One Key Cash For Every Booking Made Through The App.</p>
                    <p class="text-muted small">Secured By Europe GTP</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="mb-3">
                        <i class="fab fa-app-store fa-4x text-dark"></i>
                        <p class="small mt-2">Download on App Store</p>
                    </div>
                    <div class="mb-3">
                        <i class="fab fa-google-play fa-4x text-success"></i>
                        <p class="small mt-2">Get it on Google Play</p>
                    </div>
                    <div class="mt-3">
                        <i class="fas fa-qrcode fa-4x text-dark"></i>
                        <p class="small mt-2">Scan QR Code</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Ensure checkout is always after checkin
document.addEventListener('DOMContentLoaded', function() {
    const checkin = document.querySelector('input[name="check_in"]');
    const checkout = document.querySelector('input[name="check_out"]');
    
    checkin.addEventListener('change', function() {
        const checkinDate = new Date(this.value);
        const nextDay = new Date(checkinDate);
        nextDay.setDate(nextDay.getDate() + 1);
        
        checkout.min = nextDay.toISOString().split('T')[0];
        if (checkout.value <= this.value) {
            checkout.value = nextDay.toISOString().split('T')[0];
        }
    });
});
</script>
@endpush
@endsection