@extends('layouts.hotel-manager')

@section('title', 'Manage Rooms - ' . $hotel->name)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Manage Rooms</h1>
            <p class="text-muted">{{ $hotel->name }}</p>
        </div>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                <i class="fas fa-plus"></i> Add New Room
            </button>
            <a href="{{ route('hotel-manager.hotels.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Hotels
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Room Name</th>
                            <th>Type</th>
                            <th>Price/Night</th>
                            <th>Capacity</th>
                            <th>Available Rooms</th>
                            <th>Size</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                        <tr>
                            <td><strong>{{ $room->name }}</strong></td>
                            <td>{{ $room->type }}</td>
                            <td>${{ number_format($room->price_per_night, 0) }}</td>
                            <td>{{ $room->max_adults }} Adults, {{ $room->max_children }} Children</td>
                            <td>{{ $room->available_rooms }}</td>
                            <td>{{ $room->size_sqm ?? 'N/A' }} m²</td>
                            <td>
                                <span class="badge bg-{{ $room->is_available ? 'success' : 'secondary' }}">
                                    {{ $room->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" 
                                        onclick="editRoom({{ $room->id }}, '{{ $room->name }}', '{{ $room->type }}', '{{ addslashes($room->description ?? '') }}', {{ $room->price_per_night }}, {{ $room->max_adults }}, {{ $room->max_children }}, {{ $room->available_rooms }}, {{ $room->size_sqm ?? 0 }}, [{{ $room->amenities->pluck('id')->implode(',') }}])">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No rooms found. Add your first room!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('hotel-manager.hotels.rooms.store', $hotel) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Room Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Room Type *</label>
                            <select name="type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="Single">Single</option>
                                <option value="Double">Double</option>
                                <option value="Twin">Twin</option>
                                <option value="Suite">Suite</option>
                                <option value="Deluxe">Deluxe</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Price per Night *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="price_per_night" step="0.01" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Max Adults *</label>
                            <input type="number" name="max_adults" class="form-control" min="1" value="2" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Max Children</label>
                            <input type="number" name="max_children" class="form-control" min="0" value="0">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Available Rooms *</label>
                            <input type="number" name="available_rooms" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Size (m²)</label>
                            <input type="number" name="size_sqm" step="0.1" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amenities</label>
                        <div class="row">
                            @foreach($amenities as $amenity)
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="amenities[]" 
                                           value="{{ $amenity->id }}" id="amenity_{{ $amenity->id }}">
                                    <label class="form-check-label" for="amenity_{{ $amenity->id }}">
                                        {{ $amenity->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Room</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Room Modal -->
<div class="modal fade" id="editRoomModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editRoomForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Room Name *</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Room Type *</label>
                            <select name="type" id="edit_type" class="form-select" required>
                                <option value="Single">Single</option>
                                <option value="Double">Double</option>
                                <option value="Twin">Twin</option>
                                <option value="Suite">Suite</option>
                                <option value="Deluxe">Deluxe</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Price per Night *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="price_per_night" id="edit_price" step="0.01" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Max Adults *</label>
                            <input type="number" name="max_adults" id="edit_adults" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Max Children</label>
                            <input type="number" name="max_children" id="edit_children" class="form-control" min="0">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Available Rooms *</label>
                            <input type="number" name="available_rooms" id="edit_available" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Size (m²)</label>
                            <input type="number" name="size_sqm" id="edit_size" step="0.1" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amenities</label>
                        <div class="row" id="edit_amenities_container">
                            @foreach($amenities as $amenity)
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input edit-amenity" type="checkbox" name="amenities[]" 
                                           value="{{ $amenity->id }}" id="edit_amenity_{{ $amenity->id }}">
                                    <label class="form-check-label" for="edit_amenity_{{ $amenity->id }}">
                                        {{ $amenity->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Room</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editRoom(id, name, type, description, price, adults, children, available, size, amenities) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_type').value = type;
    document.getElementById('edit_description').value = description || '';
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_adults').value = adults;
    document.getElementById('edit_children').value = children;
    document.getElementById('edit_available').value = available;
    document.getElementById('edit_size').value = size || '';
    
    // Uncheck all amenities first
    document.querySelectorAll('.edit-amenity').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Check selected amenities
    amenities.forEach(amenityId => {
        const checkbox = document.getElementById('edit_amenity_' + amenityId);
        if (checkbox) {
            checkbox.checked = true;
        }
    });
    
    // Set form action
    document.getElementById('editRoomForm').action = 
        '{{ route("hotel-manager.hotels.rooms.update", [$hotel, ":id"]) }}'.replace(':id', id);
    
    // Show modal
    new bootstrap.Modal(document.getElementById('editRoomModal')).show();
}
</script>
@endpush
@endsection