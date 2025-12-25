@extends('layouts.app')

@section('title', 'My Profile')

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
                    <p class="small text-muted">Manage your profile, rewards, and preferences for all our brands in one place.</p>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('profile.show') }}">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-list"></i> Preferences
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-credit-card"></i> Payment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-question-circle"></i> Help
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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4>Basic Information</h4>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Profile
                        </a>
                    </div>

                    <p class="text-muted">Make Sure This Information Matches Your Travel ID, Like Your Passport Or License.</p>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Full Name</strong>
                        </div>
                        <div class="col-md-4">
                            <strong>Date Of Birth</strong>
                        </div>
                        <div class="col-md-4">
                            <strong>Bio</strong>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <p>{{ auth()->user()->name }}</p>
                        </div>
                        <div class="col-md-4">
                            <p>{{ auth()->user()->date_of_birth ? auth()->user()->date_of_birth->format('M d, Y') : 'Not Provided' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p>{{ auth()->user()->bio ?? 'Not Provided' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Disability</strong>
                        </div>
                        <div class="col-md-4">
                            <strong>Accessibility Needs</strong>
                        </div>
                        <div class="col-md-4">
                            <strong>Gender</strong>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <p>None</p>
                        </div>
                        <div class="col-md-4">
                            <p>Not Provided</p>
                        </div>
                        <div class="col-md-4">
                            <p>{{ auth()->user()->gender ? ucfirst(auth()->user()->gender) : 'Not Provided' }}</p>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-4">Contact</h5>
                    <p class="text-muted">Receive Account Activity Alerts And Trip Updates By Sharing This Information.</p>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Mobile Number</strong>
                        </div>
                        <div class="col-md-6">
                            <strong>Email</strong>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p>{{ auth()->user()->phone ?? 'Not Provided' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p>{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Emergency Contact</strong>
                        </div>
                        <div class="col-md-6">
                            <strong>Address</strong>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <p>Not Provided</p>
                        </div>
                        <div class="col-md-6">
                            <p>{{ auth()->user()->address ?? 'Not Provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection