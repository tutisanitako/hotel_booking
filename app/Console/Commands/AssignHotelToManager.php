<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Hotel;
use Illuminate\Console\Command;

class AssignHotelToManager extends Command
{
    protected $signature = 'manager:assign {email} {hotel_id}';
    protected $description = 'Assign a hotel to a hotel manager';

    public function handle()
    {
        $email = $this->argument('email');
        $hotelId = $this->argument('hotel_id');

        // Find user
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        // Check if user is a manager or admin
        if ($user->role !== 'hotel_manager' && $user->role !== 'admin') {
            $this->error("User must be a hotel manager or admin!");
            $this->warn("Current role: {$user->role}");
            $this->newLine();
            $this->line("To promote this user to manager, run:");
            $this->line("php artisan manager:promote {$email}");
            return 1;
        }

        // Find hotel
        $hotel = Hotel::find($hotelId);
        
        if (!$hotel) {
            $this->error("Hotel with ID {$hotelId} not found!");
            $this->newLine();
            $this->line("Available hotels:");
            Hotel::all()->each(function($h) {
                $this->line("  • ID: {$h->id} - {$h->name}");
            });
            return 1;
        }

        // Check if already assigned
        if ($user->managedHotels()->where('hotel_id', $hotelId)->exists()) {
            $this->warn("⚠ Hotel '{$hotel->name}' is already assigned to {$user->name}!");
            return 0;
        }

        // Assign hotel
        $user->managedHotels()->attach($hotelId);

        $this->info("✓ Successfully assigned '{$hotel->name}' to {$user->name}!");
        $this->newLine();
        $this->line("Manager now manages:");
        $user->managedHotels->each(function($h) {
            $this->line("  • {$h->name} (ID: {$h->id})");
        });
        
        return 0;
    }
}