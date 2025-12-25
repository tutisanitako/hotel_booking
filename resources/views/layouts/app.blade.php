<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EasySet24') }} - @yield('title', 'Hotel Booking')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
    
    <style>
        :root {
            --primary-color: #0066cc;
            --secondary-color: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar-brand img {
            height: 40px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #0052a3;
            border-color: #0052a3;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .hero-section {
            background: linear-gradient(135deg, #0066cc 0%, #004999 100%);
            color: white;
            padding: 100px 0;
        }
        
        footer {
            background-color: #f8f9fa;
            padding: 40px 0;
            margin-top: 60px;
        }
        
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-hotel text-primary"></i>
                <strong>EasySet 24</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Trip</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('hotels.index') }}">Hotel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">About</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Sign In</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary text-white ms-2" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i> Admin Panel
                                </a>
                            </li>
                        @endif
                        
                        @if(auth()->user()->isHotelManager())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('hotel-manager.dashboard') }}">
                                    <i class="fas fa-hotel"></i> Manager Dashboard
                                </a>
                            </li>
                        @endif
                        
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('bookings.my-bookings') }}">My Bookings</a>
                        </li>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user"></i> My Profile
                                </a></li>
                                
                                @if(auth()->user()->isAdmin())
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                                    </a></li>
                                @endif
                                
                                @if(auth()->user()->isHotelManager())
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('hotel-manager.dashboard') }}">
                                        <i class="fas fa-hotel"></i> Manager Dashboard
                                    </a></li>
                                @endif
                                
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h5>About Us</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('about') }}" class="text-decoration-none text-muted">Our Story</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Work With Us</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Press & Media</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Privacy & Security</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>We Offer</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-decoration-none text-muted">Trip Sponsorship</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Last Minutes Flights</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Best Deals</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">AI-Driven Search</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Headquarters</h5>
                    <ul class="list-unstyled text-muted">
                        <li>England</li>
                        <li>France</li>
                        <li>Canada</li>
                        <li>Iceland</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Travel Blogs</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-decoration-none text-muted">Bali Travel Guide</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Sri Travel Guide</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Peru Travel Guide</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Swiss Travel Guide</a></li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        <i class="far fa-copyright"></i> Copyright EasySet24 
                        <i class="fas fa-envelope ms-3"></i> Easyset24@Gmail.Com
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="text-muted mb-0">"EasySet24: Seamless Journeys, Unrivalled Travel Wisdom!"</p>
                    <p class="text-muted mb-0">
                        <i class="fas fa-map-marker-alt"></i> 123 Oxford Street, London 
                        <i class="fas fa-phone ms-3"></i> +44 20 7123 4567
                    </p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <i class="fab fa-cc-visa fa-3x text-primary me-3"></i>
                    <i class="fab fa-cc-amex fa-3x text-info me-3"></i>
                    <i class="fab fa-cc-mastercard fa-3x text-warning me-3"></i>
                    <i class="fab fa-cc-paypal fa-3x text-primary"></i>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>