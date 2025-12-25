<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateHotelManager extends Command
{
    protected $signature = 'manager:create {email} {password} {name}';
    protected $description = 'Create a hotel manager user';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->argument('name');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return 1;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'hotel_manager',
        ]);

        $this->info("✓ Hotel manager created successfully!");
        $this->newLine();
        $this->line("📧 Email: {$email}");
        $this->line("👤 Name: {$name}");
        $this->line("🔑 Role: hotel_manager");
        $this->newLine();
        $this->warn("Next steps:");
        $this->line("1. Assign hotels using: php artisan manager:assign {$email} <hotel_id>");
        $this->line("2. Login at: " . route('login'));
        
        return 0;
    }
}