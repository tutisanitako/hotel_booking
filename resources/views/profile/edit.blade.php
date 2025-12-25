@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if(auth()->user()->profile_picture)
                            <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
                                 alt="Profile" class="rounded-circle mb-2" 
                                 style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #0066cc;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-2" 
                                 style="width: 100px; height: 100px; font-size: 2.5rem; border: 3px solid #0066cc;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <h6 class="mt-2">Hi, {{ auth()->user()->name }}</h6>
                        <p class="text-muted small">{{ auth()->user()->email }}</p>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('profile.show') }}">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-cog"></i> Setting
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-start w-100">
                                    <i class="fas fa-sign-out-alt"></i> Sign Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Edit Profile</h4>
                    <p class="text-muted">Make Sure This Information Matches Your Travel ID, Like Your Passport Or License.</p>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Picture Upload -->
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                @if(auth()->user()->profile_picture)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
                                         alt="Profile" class="rounded-circle" 
                                         style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #0066cc;" id="preview">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" 
                                         style="width: 150px; height: 150px; font-size: 3rem; border: 4px solid #0066cc;" id="preview-placeholder">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <img src="" alt="Preview" class="rounded-circle d-none" 
                                         style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #0066cc;" id="preview">
                                @endif
                            </div>
                            <div>
                                <label for="profile_picture" class="btn btn-outline-primary">
                                    <i class="fas fa-camera"></i> Change Profile Picture
                                </label>
                                <input type="file" name="profile_picture" id="profile_picture" 
                                       class="d-none" accept="image/*" onchange="previewImage(event)">
                                <p class="text-muted small mt-2">JPG, PNG, max 2MB</p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Full Name -->
                        <h5 class="mb-3">Full name</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">First name *</label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                                       value="{{ old('first_name', explode(' ', auth()->user()->name)[0] ?? '') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last name *</label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                                       value="{{ old('last_name', explode(' ', auth()->user()->name)[1] ?? '') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email & Phone -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone', auth()->user()->phone) }}" placeholder="+1234567890">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Bio -->
                        <h5 class="mb-3">About you</h5>
                        <div class="mb-4">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="3" 
                                      placeholder="Tell us about yourself, your travel style, hobbies, interests...">{{ old('bio', auth()->user()->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date of Birth & Gender -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="mb-3">Date of birth</h5>
                                <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       value="{{ old('date_of_birth', auth()->user()->date_of_birth ? auth()->user()->date_of_birth->format('Y-m-d') : '') }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3">Gender</h5>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="female" value="female" 
                                           {{ old('gender', auth()->user()->gender) == 'female' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="female">Female</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="male" value="male" 
                                           {{ old('gender', auth()->user()->gender) == 'male' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="male">Male</label>
                                </div>
                            </div>
                        </div>

                        <!-- Country & Address -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <select name="country" class="form-select @error('country') is-invalid @enderror">
                                    <option value="">Select Country</option>
                                    <option value="United States" {{ old('country', auth()->user()->country) == 'United States' ? 'selected' : '' }}>United States</option>
                                    <option value="United Kingdom" {{ old('country', auth()->user()->country) == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                                    <option value="Canada" {{ old('country', auth()->user()->country) == 'Canada' ? 'selected' : '' }}>Canada</option>
                                    <option value="Australia" {{ old('country', auth()->user()->country) == 'Australia' ? 'selected' : '' }}>Australia</option>
                                    <option value="France" {{ old('country', auth()->user()->country) == 'France' ? 'selected' : '' }}>France</option>
                                    <option value="Germany" {{ old('country', auth()->user()->country) == 'Germany' ? 'selected' : '' }}>Germany</option>
                                    <option value="Sweden" {{ old('country', auth()->user()->country) == 'Sweden' ? 'selected' : '' }}>Sweden</option>
                                    <option value="Georgia" {{ old('country', auth()->user()->country) == 'Georgia' ? 'selected' : '' }}>Georgia</option>
                                </select>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                                       value="{{ old('address', auth()->user()->address) }}" placeholder="Street address">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        // Check file size (2MB = 2097152 bytes)
        if (file.size > 2097152) {
            alert('File size must be less than 2MB');
            event.target.value = '';
            return;
        }

        // Check file type
        if (!file.type.match('image.*')) {
            alert('Please select an image file');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview');
            const placeholder = document.getElementById('preview-placeholder');
            
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            
            if (placeholder) {
                placeholder.classList.add('d-none');
            }
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection