<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'hotel_id' => 'required|exists:hotels,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1|max:10',
            'children' => 'nullable|integer|min:0|max:10',
            'rooms_count' => 'required|integer|min:1|max:5',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'country' => 'nullable|string|max:255',
            'estimated_arrival' => 'nullable|date_format:H:i',
            'special_requests' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer',
        ];
    }

    public function messages(): array
    {
        return [
            'check_in.after_or_equal' => 'Check-in date must be today or later',
            'check_out.after' => 'Check-out date must be after check-in date',
            'adults.min' => 'At least 1 adult is required',
            'rooms_count.max' => 'Maximum 5 rooms can be booked at once',
        ];
    }
}