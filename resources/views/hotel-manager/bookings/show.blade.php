@extends('layouts.hotel-manager')

@section('title', 'Booking Details')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Booking Details: {{ $booking->booking_number }}</h1>
        <a href="{{ route('hotel-manager.bookings.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <!-- Similar to admin booking show, but simplified -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Booking Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Hotel:</strong>
                            <p>{{ $booking->hotel->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Room:</strong>
                            <p>{{ $booking->room->name }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Check-in:</strong>
                            <p>{{ $booking->check_in->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Check-out:</strong>
                            <p>{{ $booking->check_out->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Guest:</strong>
                            <p>{{ $booking->first_name }} {{ $booking->last_name }}</p>
                            <p class="text-muted">{{ $booking->email }}</p>
                            <p class="text-muted">{{ $booking->phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Guests:</strong>
                            <p>{{ $booking->adults }} Adults, {{ $booking->children }} Children</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Status Management -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Status Management</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hotel-manager.bookings.update-status', $booking) }}" method="POST" class="mb-3">
                        @csrf
                        @method('PATCH')
                        
                        <label class="form-label">Booking Status</label>
                        <select name="status" class="form-select mb-2">
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Payment</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hotel-manager.bookings.update-payment', $booking) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-select mb-2">
                            <option value="unpaid" {{ $booking->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                        
                        <button type="submit" class="btn btn-primary w-100">Update Payment</button>
                    </form>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong class="text-primary">${{ number_format($booking->total_price, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection