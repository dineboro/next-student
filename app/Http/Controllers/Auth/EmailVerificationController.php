<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailVerificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmailVerificationController extends Controller
{
    public function notice()
    {
        $userId = session('pending_verification_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->email_verified_at) {
            return redirect()->route('verification.pending-approval');
        }

        return view('auth.verify-email', compact('user'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|size:6',
        ]);

        $userId = session('pending_verification_user_id');
        $user = User::find($userId);

        if (!$user) {
            return back()->withErrors(['verification_code' => 'Invalid session. Please register again.']);
        }

        // Check if expiration date exists and is expired
        if ($user->verification_code_expires_at && now()->isAfter($user->verification_code_expires_at)) {
            return back()->withErrors(['verification_code' => 'Verification code has expired. Please request a new one.']);
        }

        // Check if verification code exists
        if (!$user->verification_code) {
            return back()->withErrors(['verification_code' => 'No verification code found. Please request a new one.']);
        }

        // Check if code matches
        if (strtoupper($request->verification_code) !== $user->verification_code) {
            \Log::warning("Failed verification attempt for user {$user->id}", [
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);

            return back()->withErrors(['verification_code' => 'Invalid verification code. Please try again.']);
        }

        // Mark email as verified
        $user->update([
            'email_verified_at' => now(),
            'verification_code' => null,
            'verification_code_expires_at' => null,
        ]);

        \Log::info("Email verified successfully for user {$user->id}", [
            'email' => $user->email,
        ]);

        return redirect()->route('verification.pending-approval')
            ->with('success', 'Email verified successfully! Your account is now pending admin approval.');
    }

    public function resend(Request $request)
    {
        $userId = session('pending_verification_user_id');
        $user = User::find($userId);

        if (!$user) {
            return back()->withErrors(['error' => 'Invalid session. Please register again.']);
        }

        if ($user->email_verified_at) {
            return redirect()->route('verification.pending-approval');
        }

        // Generate new verification code
        $verificationCode = strtoupper(Str::random(6));

        $user->update([
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => now()->addHours(24),
        ]);

        // Send new verification email
        $this->sendVerificationEmail($user, $verificationCode);

        // Log resend
        EmailVerificationLog::create([
            'user_id' => $user->id,
            'verification_code' => $verificationCode,
            'ip_address' => $request->ip(),
            'action' => 'sent',
            'action_at' => now(),
        ]);

        return back()->with('success', 'A new verification code has been sent to your email.');
    }

    public function pendingApproval()
    {
        $userId = session('pending_verification_user_id');
        $user = User::find($userId);

        if (!$user || !$user->email_verified_at) {
            return redirect()->route('login');
        }

        return view('auth.pending-approval', compact('user'));
    }

    protected function sendVerificationEmail($user, $code)
    {
        // TODO: Implement actual email sending
        \Log::info("Verification code for {$user->email}: {$code}");
    }

}
