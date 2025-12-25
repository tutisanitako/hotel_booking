@extends('layouts.app')

@section('title', 'Hotels')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Find Your Perfect Hotel</h1>
    
    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('hotels.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" 
                               value="{{ request('location') }}" placeholder="Enter city or hotel name">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Check-in</label>
                        <input type="date" name="check_in" class="form-control" 
                               value="{{ request('check_in', date('Y-m-d')) }}" 
                               min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Check-out</label>
                        <input type="date" name="check_out" class="form-control" 
                               value="{{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Min $</label>
                        <input type="number" name="min_price" class="form-control" 
                               value="{{ request('min_price') }}" placeholder="0">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Max $</label>
                        <input type="number" name="max_price" class="form-control" 
                               value="{{ request('max_price') }}" placeholder="500">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Rating</label>
                        <select name="rating" class="form-select">
                            <option value="">Any</option>
                            <option value="6" {{ request('rating') == 6 ? 'selected' : '' }}>6+</option>
                            <option value="7" {{ request('rating') == 7 ? 'selected' : '' }}>7+</option>
                            <option value="8" {{ request('rating') == 8 ? 'selected' : '' }}>8+</option>
                            <option value="9" {{ request('rating') == 9 ? 'selected' : '' }}>9+</option>
                        </select>
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

    <!-- Hotels List -->
    <div class="row">
        <div class="col-12">
            <p class="text-muted mb-3">{{ $hotels->total() }} Properties Found</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($hotels as $hotel)
        <div class="col-12">
            <div class="card">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="{{ $hotel->main_image ? asset('storage/' . $hotel->main_image) : 'https://via.placeholder.com/400x300?text=Hotel' }}" 
                             class="img-fluid h-100 w-100" style="object-fit: cover;" alt="{{ $hotel->name }}">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h4 class="card-title">{{ $hotel->name }}</h4>
                                    <p class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i> {{ $hotel->address }}
                                        @if($hotel->distance_from_center)
                                            <small>({{ $hotel->distance_from_center }}km from center)</small>
                                        @endif
                                    </p>
                                </div>
                                @if($hotel->rating > 0)
                                    <span class="badge bg-primary" style="font-size: 1.2rem;">{{ $hotel->rating }}/10</span>
                                @endif
                            </div>
                            
                            <p class="card-text">{{ Str::limit($hotel->description, 150) }}</p>
                            
                            <div class="mb-3">
                                @if($hotel->breakfast_included)
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Breakfast Included</span>
                                @endif
                                @if($hotel->rooms_count > 0)
                                    <span class="badge bg-info">{{ $hotel->rooms_count }} room types available</span>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-primary mb-0">${{ number_format($hotel->price_per_night, 0) }}</h5>
                                    <small class="text-muted">per night</small>
                                </div>
                                <a href="{{ route('hotels.show', $hotel->slug) }}?check_in={{ request('check_in', date('Y-m-d')) }}&check_out={{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}" 
                                   class="btn btn-primary">
                                    See Availability <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No hotels found matching your criteria. Please try different search parameters.
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $hotels->appends(request()->query())->links() }}
    </div>
</div>

@push('scripts')
<script>
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