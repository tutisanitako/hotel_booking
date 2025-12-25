<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PromoteToManager extends Command
{
    protected $signature = 'manager:promote {email}';
    protected $description = 'Promote an existing user to hotel manager';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ User with email {$email} not found!");
            return 1;
        }

        if ($user->role === 'hotel_manager') {
            $this->warn("⚠ {$user->name} is already a hotel manager!");
            return 0;
        }

        $oldRole = $user->role;
        $user->role = 'hotel_manager';
        $user->save();

        $this->info("✓ Successfully promoted {$user->name}!");
        $this->line("   From: '{$oldRole}' → To: 'hotel_manager'");
        $this->newLine();
        $this->line("Next step: Assign hotels using:");
        $this->line("php artisan manager:assign {$email} <hotel_id>");
        
        return 0;
    }
}