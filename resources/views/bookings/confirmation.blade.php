@extends('layouts.app')

@section('title', 'Booking Confirmed')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h1 class="mb-3">Your Hotel Reservation Confirmed</h1>
                    <p class="lead">Contact EasySet24 if you need to change any information</p>
                    <p class="text-muted">Booking Number: <strong class="text-primary">{{ $booking->booking_number }}</strong></p>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Booking Details - {{ $booking->booking_number }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Hotel Information</h6>
                            <p class="mb-1"><strong>{{ $booking->hotel->name }}</strong></p>
                            <p class="mb-1 text-muted"><i class="fas fa-map-marker-alt"></i> {{ $booking->hotel->address }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Guest Information</h6>
                            <p class="mb-1"><strong>{{ $booking->first_name }} {{ $booking->last_name }}</strong></p>
                            <p class="mb-1 text-muted"><i class="fas fa-envelope"></i> {{ $booking->email }}</p>
                            <p class="mb-1 text-muted"><i class="fas fa-phone"></i> {{ $booking->phone }}</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Check-In</th>
                                    <th>Check-Out</th>
                                    <th>Nights</th>
                                    <th>Room Type</th>
                                    <th>Guests</th>
                                    <th>Rooms</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $booking->check_in->format('d M Y') }}</td>
                                    <td>{{ $booking->check_out->format('d M Y') }}</td>
                                    <td>{{ $booking->nights }}</td>
                                    <td>{{ $booking->room->name }} ({{ $booking->room->type }})</td>
                                    <td>{{ $booking->adults }} Adults, {{ $booking->children }} Children</td>
                                    <td>{{ $booking->rooms_count }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Hotel Policies</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-clock text-primary"></i> Check-In: 14:00 Every Day</li>
                                <li><i class="fas fa-clock text-primary"></i> Check-Out: 12:00 Every Day</li>
                                <li><i class="fas fa-shield-alt text-primary"></i> Secured By EasySet24 Policy</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Payment Summary</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Original Price:</span>
                                <strong>${{ number_format($booking->original_price, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Discount (4%):</span>
                                <strong>-${{ number_format($booking->discount, 2) }}</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total Amount:</strong>
                                <strong class="text-primary h5">${{ number_format($booking->total_price, 2) }}</strong>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-{{ $booking->payment_status == 'paid' ? 'success' : 'warning' }}">
                                    Payment: {{ ucfirst($booking->payment_status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($booking->special_requests)
                    <div class="mt-4">
                        <h6 class="text-muted mb-2">Special Requests</h6>
                        <p class="text-muted">{{ $booking->special_requests }}</p>
                    </div>
                    @endif

                    @if($booking->payment_status == 'unpaid')
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle"></i> <strong>Payment Due at Hotel</strong><br>
                        You have chosen to pay at the hotel. Please bring your booking confirmation.
                    </div>
                    @endif
                </div>
            </div>

            <!-- Cancellation Policy -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Cancellation Policy</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Important Notice</strong>
                        <p class="mb-0 mt-2">This booking represents the conclusive step in the hotel reservation process. 
                        It is considered final and may only be canceled by the hotel in the event of unforeseen circumstances or natural disasters.</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('bookings.my-bookings') }}" class="btn btn-primary btn-lg me-2">
                    <i class="fas fa-list"></i> View My Bookings
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-home"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection