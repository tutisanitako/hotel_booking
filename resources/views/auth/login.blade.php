@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="row g-0">
                    <!-- Left Side - Icon Design -->
                    <div class="col-md-5">
                        <div class="bg-primary text-white h-100 d-flex align-items-center justify-content-center rounded-start">
                            <div class="text-center p-5">
                                <i class="fas fa-suitcase-rolling fa-5x mb-4"></i>
                                <h3>Welcome to EasySet24</h3>
                                <p>Your journey begins here</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Login Form -->
                    <div class="col-md-7">
                        <div class="card-body p-5">
                            <div class="text-end mb-4">
                                <i class="fas fa-globe me-2"></i>
                                <i class="fas fa-info-circle"></i>
                            </div>

                            <h3 class="mb-1">Login</h3>
                            <p class="text-muted mb-4">Login to access your account</p>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email') }}" placeholder="Example@Example.Com" required autofocus>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               placeholder="••••••••" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                        <label class="form-check-label" for="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                    <a href="{{ route('password.request') }}" class="text-primary text-decoration-none">
                                        Forgot Password?
                                    </a>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 mb-3">LOGIN</button>

                                <div class="text-center mb-3">
                                    <span class="text-muted">Or</span>
                                </div>

                                <div class="d-flex justify-content-center gap-3 mb-4">
                                    <button type="button" class="btn btn-outline-primary btn-sm">
                                        <i class="fab fa-facebook-f"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-dark btn-sm">
                                        <i class="fab fa-apple"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm">
                                        <i class="fab fa-google"></i>
                                    </button>
                                </div>

                                <div class="text-center">
                                    <span class="text-muted">Not have an account yet? </span>
                                    <a href="{{ route('register') }}" class="text-primary text-decoration-none">Register</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
@endpush
@endsection