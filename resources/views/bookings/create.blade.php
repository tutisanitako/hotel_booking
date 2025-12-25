@extends('layouts.app')

@section('title', 'Book Hotel')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Booking Form -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex align-items-center">
                        @if(auth()->user()->profile_picture)
                            <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
                                 alt="User" class="rounded-circle me-3" 
                                 style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px; font-size: 1.5rem;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h5 class="mb-0">{{ auth()->user()->name }}</h5>
                            <small class="text-muted">{{ auth()->user()->email }}</small>
                        </div>
                        <a href="{{ route('bookings.my-bookings') }}" class="btn btn-sm btn-outline-primary ms-auto">
                            Check Your Booking History
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Enter Your Information</h4>
                    <p class="text-muted">Make Sure The Information That You Have Already Written In Your Profile Is Correct.</p>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        <input type="hidden" name="check_in" value="{{ $checkIn->format('Y-m-d') }}">
                        <input type="hidden" name="check_out" value="{{ $checkOut->format('Y-m-d') }}">
                        <input type="hidden" name="adults" value="{{ $request->adults }}">
                        <input type="hidden" name="children" value="{{ $request->children ?? 0 }}">
                        <input type="hidden" name="rooms_count" value="{{ $request->rooms_count }}">

                        <!-- Guest Information -->
                        <h5 class="mb-3">Full Name</h5>
                        <div class="row mb-3">
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

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number *</label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone', auth()->user()->phone) }}" placeholder="+46xxxxxxxxx" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Country</label>
                            <select name="country" class="form-select">
                                <option value="">Select Country</option>
                                <option value="Sweden" {{ old('country', auth()->user()->country) == 'Sweden' ? 'selected' : '' }}>Sweden</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="United States">United States</option>
                                <option value="France">France</option>
                                <option value="Germany">Germany</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Special Requests (Optional)</label>
                            <textarea name="special_requests" class="form-control" rows="3" placeholder="Any special requirements?">{{ old('special_requests') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Estimated Arrival Time (Optional)</label>
                            <select name="estimated_arrival" class="form-select">
                                <option value="">Please Select</option>
                                <option value="14:00">14:00 - 15:00</option>
                                <option value="15:00">15:00 - 16:00</option>
                                <option value="16:00">16:00 - 17:00</option>
                                <option value="17:00">17:00 - 18:00</option>
                                <option value="18:00">18:00 - 19:00</option>
                                <option value="19:00">19:00 - 20:00</option>
                                <option value="20:00">20:00 - 21:00</option>
                                <option value="21:00">21:00 - 22:00</option>
                                <option value="22:00">22:00 - 23:00</option>
                            </select>
                        </div>

                        <hr class="my-4">

                        <!-- Payment Section -->
                        <h4 class="mb-4">Payment Information</h4>

                        <div class="mb-4">
                            <label class="form-label mb-3"><strong>When do you want to pay?</strong></label>
                            
                            <div class="form-check mb-3 p-3 border rounded">
                                <input class="form-check-input" type="radio" name="payment_timing" id="pay_now" value="now" checked>
                                <label class="form-check-label w-100" for="pay_now">
                                    <strong><i class="fas fa-credit-card text-primary"></i> Pay Now</strong>
                                    <p class="text-muted small mb-0 ms-4">Secure your booking with immediate payment</p>
                                </label>
                            </div>
                            
                            <div class="form-check p-3 border rounded">
                                <input class="form-check-input" type="radio" name="payment_timing" id="pay_later" value="later">
                                <label class="form-check-label w-100" for="pay_later">
                                    <strong><i class="fas fa-hotel text-success"></i> Pay at Hotel</strong>
                                    <p class="text-muted small mb-0 ms-4">Pay when you arrive at the hotel</p>
                                </label>
                            </div>
                        </div>

                        <!-- Payment Details (shown/hidden by JavaScript) -->
                        <div id="payment_details_section">
                            <h5 class="mb-3">Bank Card Information</h5>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">Full Name On The Card *</label>
                                    <input type="text" class="form-control" name="card_name" id="card_name"
                                           value="{{ old('card_name') }}" placeholder="John Doe">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Card Number *</label>
                                    <input type="text" class="form-control" name="card_number" id="card_number"
                                           value="{{ old('card_number') }}" placeholder="1234 5678 9012 3456">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">EXP Date *</label>
                                    <input type="text" class="form-control" name="exp_date" id="exp_date"
                                           value="{{ old('exp_date') }}" placeholder="MM/YY">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">CVC *</label>
                                    <input type="text" class="form-control" name="cvc" id="cvc"
                                           value="{{ old('cvc') }}" placeholder="123" maxlength="3">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment Method *</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input payment-input" type="radio" name="payment_method" id="credit_card" value="credit_card" checked>
                                        <label class="form-check-label" for="credit_card">
                                            <i class="fab fa-cc-visa fa-2x text-primary"></i>
                                            <i class="fab fa-cc-mastercard fa-2x text-warning"></i>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input payment-input" type="radio" name="payment_method" id="paypal" value="paypal">
                                        <label class="form-check-label" for="paypal">
                                            <i class="fab fa-cc-paypal fa-2x text-primary"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="pay_later_message" class="alert alert-info" style="display: none;">
                            <i class="fas fa-info-circle"></i> <strong>Pay at Hotel Selected</strong>
                            <p class="mb-0">You will pay the full amount at the hotel upon arrival. Please bring a valid credit card for check-in.</p>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('hotels.show', $hotel->slug) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-check"></i> Complete Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Booking Summary - Fixed Sticky -->
        <div class="col-md-4">
            <div style="position: sticky; top: 20px;">
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Booking Summary</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-3">{{ $hotel->name }}</h6>
                        <p class="small text-muted mb-3">{{ $hotel->address }}</p>

                        <hr>

                        <div class="mb-2">
                            <strong>Check-In:</strong>
                            <p class="mb-0">{{ $checkIn->format('D, M d, Y') }}</p>
                        </div>

                        <div class="mb-2">
                            <strong>Check-Out:</strong>
                            <p class="mb-0">{{ $checkOut->format('D, M d, Y') }}</p>
                        </div>

                        <div class="mb-3">
                            <strong>Duration:</strong>
                            <p class="mb-0">{{ $nights }} Night(s)</p>
                        </div>

                        <hr>

                        <div class="mb-2">
                            <strong>Room:</strong>
                            <p class="mb-0">{{ $room->name }}</p>
                        </div>

                        <div class="mb-3">
                            <strong>Guests:</strong>
                            <p class="mb-0">{{ $request->adults }} Adults, {{ $request->children ?? 0 }} Children</p>
                        </div>

                        <hr>

                        <h6 class="mb-3">Price Breakdown</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Room Rate × {{ $nights }} nights</span>
                            <span>${{ number_format($originalPrice, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-success mb-2">
                            <span>Loyalty Discount (4%)</span>
                            <span>-${{ number_format($discount, 2) }}</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total</strong>
                            <strong class="text-primary h5">${{ number_format($totalPrice, 2) }}</strong>
                        </div>

                        <div class="alert alert-light small">
                            <i class="fas fa-info-circle"></i> Includes all taxes and fees
                        </div>
                    </div>
                </div>

                <!-- Cancellation Policy - Now properly positioned -->
                <div class="card">
                    <div class="card-body">
                        <h6>Cancellation Policy</h6>
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-check"></i> <strong>Free Cancellation</strong>
                            <p class="small mb-0">Cancel up to 24 hours before check-in</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const payNow = document.getElementById('pay_now');
    const payLater = document.getElementById('pay_later');
    const paymentSection = document.getElementById('payment_details_section');
    const payLaterMessage = document.getElementById('pay_later_message');
    
    const cardInputs = ['card_name', 'card_number', 'exp_date', 'cvc'];
    const paymentMethodInputs = document.querySelectorAll('.payment-input');
    
    function togglePaymentSection() {
        if (payLater.checked) {
            // Hide payment section
            paymentSection.style.display = 'none';
            payLaterMessage.style.display = 'block';
            
            // Remove required from payment fields
            cardInputs.forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    input.removeAttribute('required');
                }
            });
            
            paymentMethodInputs.forEach(input => {
                input.removeAttribute('required');
            });
        } else {
            // Show payment section
            paymentSection.style.display = 'block';
            payLaterMessage.style.display = 'none';
            
            // Add required to payment fields
            cardInputs.forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    input.setAttribute('required', 'required');
                }
            });
            
            paymentMethodInputs.forEach(input => {
                input.setAttribute('required', 'required');
            });
        }
    }
    
    // Add event listeners
    payNow.addEventListener('change', togglePaymentSection);
    payLater.addEventListener('change', togglePaymentSection);
    
    // Initial state
    togglePaymentSection();
});
</script>
@endpush
@endsection