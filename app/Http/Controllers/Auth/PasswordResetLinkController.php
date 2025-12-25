<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PasswordResetLinkController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'We could not find a user with that email address.',
        ]);

        // Generate a simple 6-digit code
        $code = random_int(100000, 999999);
        
        // Store in database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($code),
                'plain_token' => (string)$code, // Store plain for verification
                'created_at' => Carbon::now()
            ]
        );

        // Output to terminal with more visibility
        $separator = str_repeat('=', 60);
        echo "\n\n";
        echo "\033[1;32m" . $separator . "\033[0m\n"; // Green color
        echo "\033[1;33m       PASSWORD RESET CODE GENERATED       \033[0m\n"; // Yellow color
        echo "\033[1;32m" . $separator . "\033[0m\n";
        echo "\033[1;36mEmail:\033[0m " . $request->email . "\n"; // Cyan color
        echo "\033[1;31mReset Code:\033[0m \033[1;33m" . $code . "\033[0m\n"; // Red label, Yellow code
        echo "\033[1;36mValid for:\033[0m 15 minutes\n";
        echo "\033[1;32m" . $separator . "\033[0m\n\n";

        // Also log it to Laravel log file
        Log::channel('single')->info('========================================');
        Log::channel('single')->info('PASSWORD RESET CODE GENERATED');
        Log::channel('single')->info('========================================');
        Log::channel('single')->info('Email: ' . $request->email);
        Log::channel('single')->info('Reset Code: ' . $code);
        Log::channel('single')->info('Valid for: 15 minutes');
        Log::channel('single')->info('========================================');

        // Also write to a dedicated file
        $logContent = "\n========================================\n";
        $logContent .= "PASSWORD RESET CODE\n";
        $logContent .= "Generated at: " . now()->format('Y-m-d H:i:s') . "\n";
        $logContent .= "========================================\n";
        $logContent .= "Email: " . $request->email . "\n";
        $logContent .= "Reset Code: " . $code . "\n";
        $logContent .= "Valid for: 15 minutes\n";
        $logContent .= "========================================\n";
        
        file_put_contents(storage_path('logs/password-resets.log'), $logContent, FILE_APPEND);

        return redirect()->route('password.code')
            ->with('email', $request->email)
            ->with('status', 'Reset code generated! Check your terminal or storage/logs/password-resets.log file for the code.');
    }
}