@extends('layouts.admin')

@section('title', 'Manage Hotels')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Hotels</h1>
        <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Hotel
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Price/Night</th>
                            <th>Rating</th>
                            <th>Rooms</th>
                            <th>Bookings</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hotels as $hotel)
                        <tr>
                            <td>{{ $hotel->id }}</td>
                            <td>
                                <strong>{{ $hotel->name }}</strong>
                                @if($hotel->is_featured)
                                    <span class="badge bg-warning text-dark">Featured</span>
                                @endif
                            </td>
                            <td>{{ $hotel->location }}</td>
                            <td>${{ number_format($hotel->price_per_night, 0) }}</td>
                            <td>
                                @if($hotel->rating > 0)
                                    <span class="badge bg-primary">{{ $hotel->rating }}/10</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $hotel->rooms_count }}</td>
                            <td>{{ $hotel->bookings_count }}</td>
                            <td>
                                <span class="badge bg-{{ $hotel->is_active ? 'success' : 'secondary' }}">
                                    {{ $hotel->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.hotels.rooms', $hotel) }}" 
                                       class="btn btn-sm btn-info" title="Manage Rooms">
                                        <i class="fas fa-bed"></i>
                                    </a>
                                    <a href="{{ route('admin.hotels.edit', $hotel) }}" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.hotels.destroy', $hotel) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this hotel?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No hotels found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $hotels->links() }}
    </div>
</div>
@endsection