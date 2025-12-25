<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'location',
        'address',
        'latitude',
        'longitude',
        'distance_from_center',
        'rating',
        'price_per_night',
        'main_image',
        'breakfast_included',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'breakfast_included' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price_per_night' => 'decimal:2',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Hotel managers relationship
    public function managers()
    {
        return $this->belongsToMany(User::class, 'hotel_user');
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?? 0;
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->where('is_approved', true)->count();
    }
}