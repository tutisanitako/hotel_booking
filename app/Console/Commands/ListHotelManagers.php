<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListHotelManagers extends Command
{
    protected $signature = 'manager:list';
    protected $description = 'List all hotel managers and their assigned hotels';

    public function handle()
    {
        $managers = User::where('role', 'hotel_manager')
            ->with('managedHotels')
            ->get();

        if ($managers->isEmpty()) {
            $this->warn("⚠ No hotel managers found.");
            $this->newLine();
            $this->line("Create one using:");
            $this->line("php artisan manager:create <email> <password> <name>");
            return 0;
        }

        $this->info("📋 Hotel Managers:");
        $this->newLine();

        foreach ($managers as $manager) {
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->info("👤 {$manager->name}");
            $this->line("   📧 Email: {$manager->email}");
            $this->line("   🆔 ID: {$manager->id}");
            
            if ($manager->managedHotels->isEmpty()) {
                $this->warn("   ⚠ No hotels assigned");
                $this->line("   Assign with: php artisan manager:assign {$manager->email} <hotel_id>");
            } else {
                $this->line("   🏨 Manages {$manager->managedHotels->count()} hotel(s):");
                foreach ($manager->managedHotels as $hotel) {
                    $this->line("      • {$hotel->name} (ID: {$hotel->id})");
                }
            }
            $this->newLine();
        }
        
        return 0;
    }
}