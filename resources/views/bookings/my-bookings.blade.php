@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">My Bookings</h1>

    @forelse($bookings as $booking)
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <img src="{{ $booking->hotel->main_image ? asset('storage/' . $booking->hotel->main_image) : 'https://via.placeholder.com/300x200?text=Hotel' }}" 
                         class="img-fluid rounded" alt="{{ $booking->hotel->name }}">
                </div>
                <div class="col-md-6">
                    <h5>{{ $booking->hotel->name }}</h5>
                    <p class="text-muted mb-1">
                        <i class="fas fa-map-marker-alt"></i> {{ $booking->hotel->address }}
                    </p>
                    <p class="mb-1"><strong>Booking Number:</strong> {{ $booking->booking_number }}</p>
                    <p class="mb-1"><strong>Room:</strong> {{ $booking->room->name }}</p>
                    <p class="mb-1">
                        <strong>Dates:</strong> {{ $booking->check_in->format('M d, Y') }} - {{ $booking->check_out->format('M d, Y') }}
                        ({{ $booking->nights }} nights)
                    </p>
                    <p class="mb-1"><strong>Guests:</strong> {{ $booking->adults }} Adults, {{ $booking->children }} Children</p>
                    <div class="mt-2">
                        <span class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'cancelled' ? 'danger' : 'warning') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                        <span class="badge bg-{{ $booking->payment_status == 'paid' ? 'success' : 'warning' }}">
                            Payment: {{ ucfirst($booking->payment_status) }}
                        </span>
                    </div>
                </div>
                <div class="col-md-3 text-end">
                    <h4 class="text-primary">${{ number_format($booking->total_price, 2) }}</h4>
                    <p class="text-muted small">Total Amount</p>
                    <a href="{{ route('bookings.confirmation', $booking->booking_number) }}" class="btn btn-outline-primary btn-sm">
                        View Details
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> You don't have any bookings yet. 
        <a href="{{ route('hotels.index') }}">Browse hotels</a> to make your first booking!
    </div>
    @endforelse

    <!-- Pagination -->
    <div class="mt-4">
        {{ $bookings->links() }}
    </div>
</div>
@endsection