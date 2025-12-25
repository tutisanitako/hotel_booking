@extends('layouts.admin')

@section('title', 'Edit Hotel')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Hotel: {{ $hotel->name }}</h1>
        <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Hotels
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.hotels.update', $hotel) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hotel Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $hotel->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Location *</label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" 
                               value="{{ old('location', $hotel->location) }}" required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description *</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                              rows="4" required>{{ old('description', $hotel->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Address *</label>
                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                           value="{{ old('address', $hotel->address) }}" required>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Price per Night *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="price_per_night" step="0.01" 
                                   class="form-control @error('price_per_night') is-invalid @enderror" 
                                   value="{{ old('price_per_night', $hotel->price_per_night) }}" required>
                            @error('price_per_night')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Rating (0-10)</label>
                        <input type="number" name="rating" min="0" max="10" 
                               class="form-control @error('rating') is-invalid @enderror" 
                               value="{{ old('rating', $hotel->rating) }}">
                        @error('rating')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Distance from Center (km)</label>
                        <input type="number" name="distance_from_center" step="0.1" 
                               class="form-control @error('distance_from_center') is-invalid @enderror" 
                               value="{{ old('distance_from_center', $hotel->distance_from_center) }}">
                        @error('distance_from_center')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Main Image</label>
                        <input type="file" name="main_image" class="form-control @error('main_image') is-invalid @enderror" 
                               accept="image/*">
                        @error('main_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($hotel->main_image)
                            <small class="text-muted">Current image uploaded</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="breakfast_included" 
                                   id="breakfast_included" value="1" 
                                   {{ old('breakfast_included', $hotel->breakfast_included) ? 'checked' : '' }}>
                            <label class="form-check-label" for="breakfast_included">
                                Breakfast Included
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" 
                                   id="is_featured" value="1" 
                                   {{ old('is_featured', $hotel->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured Hotel
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" 
                                   id="is_active" value="1" 
                                   {{ old('is_active', $hotel->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Hotel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection