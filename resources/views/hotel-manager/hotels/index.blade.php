@extends('layouts.hotel-manager')

@section('title', 'My Hotels')

@section('content')
<div class="container-fluid py-4">
    <h1 class="mb-4">My Hotels</h1>

    <div class="row g-4">
        @forelse($hotels as $hotel)
        <div class="col-md-6">
            <div class="card h-100">
                <div class="row g-0">
                    <div class="col-md-4">
                        @if($hotel->main_image)
                            <img src="{{ asset('storage/' . $hotel->main_image) }}" 
                                 class="img-fluid h-100 w-100" style="object-fit: cover;" alt="{{ $hotel->name }}">
                        @else
                            <div class="bg-secondary h-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-hotel fa-4x text-white"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $hotel->name }}</h5>
                            <p class="text-muted small mb-2">{{ $hotel->location }}</p>
                            
                            <div class="mb-3">
                                <span class="badge bg-{{ $hotel->is_active ? 'success' : 'secondary' }}">
                                    {{ $hotel->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($hotel->is_featured)
                                    <span class="badge bg-warning">Featured</span>
                                @endif
                            </div>

                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <strong>{{ $hotel->rooms_count }}</strong>
                                    <small class="d-block text-muted">Rooms</small>
                                </div>
                                <div class="col-6">
                                    <strong>{{ $hotel->bookings_count }}</strong>
                                    <small class="d-block text-muted">Bookings</small>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('hotel-manager.hotels.edit', $hotel) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit Hotel
                                </a>
                                <a href="{{ route('hotel-manager.hotels.rooms', $hotel) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-bed"></i> Manage Rooms
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
                <i class="fas fa-info-circle"></i> No hotels assigned yet. Contact the administrator to assign hotels to your account.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection