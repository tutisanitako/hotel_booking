@extends('layouts.app')

@section('title', $hotel->name)

@section('content')
<div class="container my-5">
    <!-- Hotel Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>{{ $hotel->name }}</h1>
            <p class="text-muted">
                <i class="fas fa-map-marker-alt"></i> {{ $hotel->address }}
                @if($hotel->reviews_count > 0)
                    <span class="ms-3">
                        <i class="fas fa-star text-warning"></i> 
                        {{ number_format($hotel->average_rating, 1) }}/10 
                        ({{ $hotel->reviews_count }} reviews)
                    </span>
                @endif
            </p>
        </div>
        <div class="col-md-4 text-end">
            @if($hotel->rating > 0)
                <span class="badge bg-primary" style="font-size: 1.5rem;">{{ $hotel->rating }}/10</span>
            @endif
        </div>
    </div>

    <!-- Hotel Images -->
    <div class="row mb-4">
        <div class="col-12">
            <img src="{{ $hotel->main_image ? asset('storage/' . $hotel->main_image) : 'https://via.placeholder.com/1200x400?text=Hotel' }}" 
                 class="img-fluid w-100 rounded" style="max-height: 400px; object-fit: cover;" alt="{{ $hotel->name }}">
        </div>
    </div>

    <!-- Date Selection Card -->
    <div class="card mb-4 bg-light">
        <div class="card-body">
            <form action="{{ route('hotels.show', $hotel->slug) }}" method="GET" id="dateForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label"><strong>Check-in</strong></label>
                        <input type="date" name="check_in" class="form-control" 
                               value="{{ request('check_in', date('Y-m-d')) }}" 
                               min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><strong>Check-out</strong></label>
                        <input type="date" name="check_out" class="form-control" 
                               value="{{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label"><strong>Adults</strong></label>
                        <select name="adults" class="form-select">
                            <option value="1" {{ request('adults') == 1 ? 'selected' : '' }}>1 Adult</option>
                            <option value="2" {{ request('adults', 2) == 2 ? 'selected' : '' }}>2 Adults</option>
                            <option value="3" {{ request('adults') == 3 ? 'selected' : '' }}>3 Adults</option>
                            <option value="4" {{ request('adults') == 4 ? 'selected' : '' }}>4 Adults</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label"><strong>Children</strong></label>
                        <select name="children" class="form-select">
                            <option value="0" {{ request('children', 0) == 0 ? 'selected' : '' }}>0</option>
                            <option value="1" {{ request('children') == 1 ? 'selected' : '' }}>1</option>
                            <option value="2" {{ request('children') == 2 ? 'selected' : '' }}>2</option>
                            <option value="3" {{ request('children') == 3 ? 'selected' : '' }}>3</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Update Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="hotelTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button">
                Place Details
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="prices-tab" data-bs-toggle="tab" data-bs-target="#prices" type="button">
                Info & Prices
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rooms-tab" data-bs-toggle="tab" data-bs-target="#rooms" type="button">
                Rooms & Beds
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rules-tab" data-bs-toggle="tab" data-bs-target="#rules" type="button">
                Place Rules
            </button>
        </li>
    </ul>

    <div class="tab-content" id="hotelTabsContent">
        <!-- Place Details Tab -->
        <div class="tab-pane fade show active" id="details" role="tabpanel">
            <div class="row">
                <div class="col-md-8">
                    <h4 class="mb-3">About This Property</h4>
                    <p>{{ $hotel->description }}</p>
                    
                    <h5 class="mt-4">Amenities</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-wifi text-primary"></i> Free WiFi</li>
                                <li><i class="fas fa-utensils text-primary"></i> Restaurant</li>
                                <li><i class="fas fa-concierge-bell text-primary"></i> Room Service</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-snowflake text-primary"></i> Air Conditioning</li>
                                <li><i class="fas fa-parking text-primary"></i> Parking Available</li>
                                <li><i class="fas fa-dumbbell text-primary"></i> Fitness Center</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Location</h5>
                            <p class="text-muted">{{ $hotel->address }}</p>
                            @if($hotel->distance_from_center)
                                <p><strong>Distance from center:</strong> {{ $hotel->distance_from_center }}km</p>
                            @endif
                            
                            <div style="height: 250px; border-radius: 8px; overflow: hidden;">
                                <iframe
                                    width="100%"
                                    height="250"
                                    frameborder="0"
                                    scrolling="no"
                                    marginheight="0"
                                    marginwidth="0"
                                    src="https://www.openstreetmap.org/export/embed.html?bbox={{ $hotel->longitude-0.01 }},{{ $hotel->latitude-0.01 }},{{ $hotel->longitude+0.01 }},{{ $hotel->latitude+0.01 }}&layer=mapnik&marker={{ $hotel->latitude }},{{ $hotel->longitude }}"
                                    style="border: 1px solid #ddd">
                                </iframe>
                            </div>
                            
                            <div class="mt-3">
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($hotel->address) }}" 
                                   target="_blank" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-map-marker-alt"></i> Open in Google Maps
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info & Prices Tab -->
        <div class="tab-pane fade" id="prices" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <h4>Price Information</h4>
                    <p><strong>Starting from:</strong> <span class="text-primary h4">${{ number_format($hotel->price_per_night, 2) }}</span> per night</p>
                    @if($hotel->breakfast_included)
                        <p class="text-success"><i class="fas fa-check"></i> Breakfast included in the price</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rooms & Beds Tab -->
        <div class="tab-pane fade" id="rooms" role="tabpanel">
            <h4 class="mb-4">Available Rooms</h4>
            @forelse($hotel->rooms as $room)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>{{ $room->name }}</h5>
                            <p class="text-muted">{{ $room->description }}</p>
                            <p>
                                <i class="fas fa-user"></i> {{ $room->max_adults }} Adults
                                @if($room->max_children > 0)
                                    , {{ $room->max_children }} Children
                                @endif
                                @if($room->size_sqm)
                                    <span class="ms-3"><i class="fas fa-expand"></i> {{ $room->size_sqm }}m²</span>
                                @endif
                            </p>
                            <div>
                                @foreach($room->amenities as $amenity)
                                    <span class="badge bg-light text-dark">{{ $amenity->name }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <h4 class="text-primary">${{ number_format($room->price_per_night, 0) }}</h4>
                            <p class="text-muted small">per night</p>
                            @if($room->available_rooms > 0)
                                <p class="text-success small">{{ $room->available_rooms }} rooms left</p>
                                @auth
                                    <a href="{{ route('bookings.create', ['hotelSlug' => $hotel->slug]) }}?room_id={{ $room->id }}&check_in={{ request('check_in', date('Y-m-d')) }}&check_out={{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}&adults={{ request('adults', 2) }}&children={{ request('children', 0) }}&rooms_count=1" 
                                       class="btn btn-primary">Book Now</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary">Login to Book</a>
                                @endauth
                            @else
                                <p class="text-danger">Sold Out</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="alert alert-info">
                No rooms available at this time. Please check back later.
            </div>
            @endforelse
        </div>

        <!-- Place Rules Tab -->
        <div class="tab-pane fade" id="rules" role="tabpanel">
            <h4 class="mb-3">Hotel Policies</h4>
            <div class="row">
                <div class="col-md-6">
                    <h5>Check-in / Check-out</h5>
                    <p><strong>Check-in:</strong> From 14:00</p>
                    <p><strong>Check-out:</strong> Until 12:00</p>
                </div>
                <div class="col-md-6">
                    <h5>Important Information</h5>
                    <ul>
                        <li>Valid ID required at check-in</li>
                        <li>Credit card for incidentals</li>
                        <li>Non-smoking rooms available</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    @if($hotel->reviews->count() > 0)
    <div class="mt-5">
        <h3 class="mb-4">Guest Reviews</h3>
        @foreach($hotel->reviews as $review)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6>{{ $review->user->name }}</h6>
                        <p class="text-muted small">
                            @if($review->country)
                                <i class="fas fa-flag"></i> {{ $review->country }}
                            @endif
                        </p>
                    </div>
                    <span class="badge bg-primary">{{ $review->rating }}/10</span>
                </div>
                <p class="mt-2">{{ $review->comment }}</p>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkin = document.querySelector('input[name="check_in"]');
    const checkout = document.querySelector('input[name="check_out"]');
    
    if (checkin && checkout) {
        checkin.addEventListener('change', function() {
            const checkinDate = new Date(this.value);
            const nextDay = new Date(checkinDate);
            nextDay.setDate(nextDay.getDate() + 1);
            
            checkout.min = nextDay.toISOString().split('T')[0];
            if (checkout.value <= this.value) {
                checkout.value = nextDay.toISOString().split('T')[0];
            }
        });
    }
});
</script>
@endpush
@endsection