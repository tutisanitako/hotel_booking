<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class PasswordResetCodeController extends Controller
{
    public function showCodeForm()
    {
        return view('auth.reset-code');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|numeric|digits:6',
        ]);

        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('plain_token', $request->code)
            ->first();

        if (!$tokenData) {
            return back()->withErrors(['code' => 'Invalid reset code.']);
        }

        // Check if token is expired (15 minutes)
        $createdAt = Carbon::parse($tokenData->created_at);
        if (Carbon::now()->diffInMinutes($createdAt) > 15) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['code' => 'Reset code has expired. Please request a new one.']);
        }

        // Code is valid, redirect to password reset form
        return redirect()->route('password.reset.form')
            ->with([
                'email' => $request->email,
                'code_verified' => true
            ]);
    }

    public function showResetForm(Request $request)
    {
        // Check if code was verified
        if (!session('code_verified')) {
            return redirect()->route('password.request')
                ->with('error', 'Please verify your reset code first.');
        }

        return view('auth.reset-password-with-code', [
            'email' => session('email')
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Verify the email has a valid token
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenData) {
            return back()->withErrors(['email' => 'Invalid reset request.']);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the reset token
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return redirect()->route('login')
            ->with('status', 'Password has been reset successfully! Please login.');
    }
}