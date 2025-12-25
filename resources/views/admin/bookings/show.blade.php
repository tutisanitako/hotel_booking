@extends('layouts.admin')

@section('title', 'Booking Details')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Booking Details: {{ $booking->booking_number }}</h1>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Bookings
        </a>
    </div>

    <div class="row g-4">
        <!-- Booking Information -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Booking Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Booking Number:</strong>
                            <p>{{ $booking->booking_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Booking Date:</strong>
                            <p>{{ $booking->created_at->format('M d, Y H:i') }}</p>
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
                        <div class="col-md-12">
                            <strong>Duration:</strong>
                            <p>{{ $booking->nights }} nights</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Guests:</strong>
                            <p>{{ $booking->adults }} Adults, {{ $booking->children }} Children</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Rooms:</strong>
                            <p>{{ $booking->rooms_count }} room(s)</p>
                        </div>
                    </div>

                    @if($booking->special_requests)
                    <div class="mb-3">
                        <strong>Special Requests:</strong>
                        <p>{{ $booking->special_requests }}</p>
                    </div>
                    @endif

                    @if($booking->estimated_arrival)
                    <div class="mb-3">
                        <strong>Estimated Arrival:</strong>
                        <p>{{ $booking->estimated_arrival }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Guest Information -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Guest Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Name:</strong>
                            <p>{{ $booking->first_name }} {{ $booking->last_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong>
                            <p>{{ $booking->email }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Phone:</strong>
                            <p>{{ $booking->phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Country:</strong>
                            <p>{{ $booking->country ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($booking->user)
                    <div class="mb-3">
                        <strong>User Account:</strong>
                        <p>
                            <a href="{{ route('admin.users.show', $booking->user) }}">
                                {{ $booking->user->name }}
                            </a>
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Hotel & Room Information -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Hotel & Room Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Hotel:</strong>
                        <p>
                            <a href="{{ route('admin.hotels.edit', $booking->hotel) }}">
                                {{ $booking->hotel->name }}
                            </a>
                        </p>
                        <p class="text-muted">{{ $booking->hotel->address }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Room Type:</strong>
                        <p>{{ $booking->room->name }} ({{ $booking->room->type }})</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status & Payment -->
        <div class="col-md-4">
            <!-- Status Management -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Status Management</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label class="form-label">Booking Status</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Payment Management -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Payment Management</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bookings.update-payment', $booking) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="unpaid" {{ $booking->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="refunded" {{ $booking->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            Update Payment Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Price Summary -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Price Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Original Price:</span>
                        <strong>${{ number_format($booking->original_price, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Discount:</span>
                        <strong>-${{ number_format($booking->discount, 2) }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total Amount:</strong>
                        <strong class="text-primary">${{ number_format($booking->total_price, 2) }}</strong>
                    </div>
                    
                    @if($booking->payment_method)
                    <div class="mt-3">
                        <small class="text-muted">
                            Payment Method: {{ ucfirst(str_replace('_', ' ', $booking->payment_method)) }}
                        </small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection