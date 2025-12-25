<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_number',
        'user_id',
        'hotel_id',
        'room_id',
        'check_in',
        'check_out',
        'nights',
        'adults',
        'children',
        'rooms_count',
        'original_price',
        'discount',
        'total_price',
        'status',
        'payment_status',
        'payment_method',
        'special_requests',
        'first_name',
        'last_name',
        'email',
        'phone',
        'country',
        'estimated_arrival',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'original_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public static function generateBookingNumber()
    {
        return 'BK' . strtoupper(uniqid());
    }
}