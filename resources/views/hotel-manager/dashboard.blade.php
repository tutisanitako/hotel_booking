@extends('layouts.hotel-manager')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <h1 class="mb-4">Hotel Manager Dashboard</h1>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">My Hotels</h6>
                            <h2 class="mb-0">{{ $stats['total_hotels'] }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-hotel fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Rooms</h6>
                            <h2 class="mb-0">{{ $stats['total_rooms'] }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-bed fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Today's Check-ins</h6>
                            <h2 class="mb-0">{{ $stats['today_checkins'] }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Pending Payments</h6>
                            <h2 class="mb-0">{{ $stats['pending_payments'] }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-dollar-sign fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Bookings -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Recent Bookings</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Booking #</th>
                                    <th>Hotel</th>
                                    <th>Guest</th>
                                    <th>Check-in</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBookings as $booking)
                                <tr>
                                    <td>
                                        <a href="{{ route('hotel-manager.bookings.show', $booking) }}">
                                            {{ $booking->booking_number }}
                                        </a>
                                    </td>
                                    <td>{{ $booking->hotel->name }}</td>
                                    <td>{{ $booking->first_name }} {{ $booking->last_name }}</td>
                                    <td>{{ $booking->check_in->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $booking->payment_status == 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($booking->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No bookings yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Hotels -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">My Hotels</h5>
                </div>
                <div class="card-body">
                    @forelse($hotels as $hotel)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-0">{{ $hotel->name }}</h6>
                            <small class="text-muted">{{ $hotel->rooms->count() }} rooms</small>
                        </div>
                        <a href="{{ route('hotel-manager.hotels.edit', $hotel) }}" class="btn btn-sm btn-outline-primary">
                            Manage
                        </a>
                    </div>
                    @empty
                    <p class="text-muted">No hotels assigned yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection