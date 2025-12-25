<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:hotels,name',
            'description' => 'required|string|min:50',
            'location' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'distance_from_center' => 'nullable|numeric|min:0',
            'rating' => 'nullable|integer|min:0|max:10',
            'price_per_night' => 'required|numeric|min:0',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'breakfast_included' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Hotel name is required',
            'name.unique' => 'A hotel with this name already exists',
            'description.min' => 'Description must be at least 50 characters',
            'price_per_night.required' => 'Price per night is required',
            'main_image.image' => 'The file must be an image',
            'main_image.max' => 'Image size must not exceed 2MB',
        ];
    }
}