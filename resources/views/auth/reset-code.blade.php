@extends('layouts.app')

@section('title', 'Enter Reset Code')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-key fa-3x text-primary mb-3"></i>
                        <h3 class="mb-3">Enter Reset Code</h3>
                        <p class="text-muted mb-4">Enter the 6-digit code from your terminal/console</p>
                    </div>

                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.verify-code') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" 
                                   value="{{ session('email', old('email')) }}" required readonly>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">6-Digit Reset Code</label>
                            <input type="text" name="code" class="form-control text-center" 
                                   placeholder="000000" maxlength="6" required autofocus
                                   style="font-size: 1.5rem; letter-spacing: 0.5rem;">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            Verify Code
                        </button>

                        <div class="text-center">
                            <a href="{{ route('password.request') }}" class="text-primary text-decoration-none">
                                <i class="fas fa-arrow-left"></i> Request New Code
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection