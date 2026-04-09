<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailVerificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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

        // Passes $user to the view — fixes "Undefined variable $user"
        return view('auth.verify-email', compact('user'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string',
        ]);

        $userId = session('pending_verification_user_id');
        $user   = User::find($userId);

        if (!$user) {
            return back()->withErrors(['verification_code' => 'Session expired. Please register again.']);
        }

        if ($user->email_verified_at) {
            return redirect()->route('login')->with('info', 'Email already verified.');
        }

        if ($user->verification_code_expires_at < now()) {
            return back()->withErrors(['verification_code' => 'This code has expired. Please request a new one.']);
        }

        if (strtoupper($request->verification_code) !== strtoupper($user->verification_code)) {
            return back()->withErrors(['verification_code' => 'Invalid verification code. Please try again.']);
        }

        $updateData = [
            'email_verified_at'            => now(),
            'verification_code'            => null,
            'verification_code_expires_at' => null,
        ];

        // Students are immediately approved — instructors stay pending until admin approves
        if ($user->role === 'student') {
            $updateData['approval_status'] = 'approved';
            $updateData['approved_at']     = now();
        }

        $user->update($updateData);

        EmailVerificationLog::create([
            'user_id'           => $user->id,
            'verification_code' => $request->verification_code,
            'ip_address'        => $request->ip(),
            'action'            => 'verified',
            'action_at'         => now(),
        ]);

        $request->session()->forget('pending_verification_user_id');

        if ($user->role === 'instructor') {
            session(['pending_verification_user_id' => $user->id]);
            return redirect()->route('verification.pending-approval')
                ->with('success', 'Email verified! Your account is pending admin review. You\'ll be notified once approved.');
        }

        Auth::login($user);
        return redirect()->route('student.dashboard')
            ->with('success', 'Email verified! Welcome to Kirkwood Help.');
    }

    public function resend(Request $request)
    {
        $userId = session('pending_verification_user_id');
        $user   = User::find($userId);

        if (!$user) {
            return back()->withErrors(['error' => 'Session expired. Please register again.']);
        }

        if ($user->email_verified_at) {
            return redirect()->route('login');
        }

        $verificationCode = strtoupper(Str::random(6));

        $user->update([
            'verification_code'            => $verificationCode,
            'verification_code_expires_at' => now()->addHours(24),
        ]);

        $this->sendVerificationEmail($user, $verificationCode);

        EmailVerificationLog::create([
            'user_id'           => $user->id,
            'verification_code' => $verificationCode,
            'ip_address'        => $request->ip(),
            'action'            => 'resent',
            'action_at'         => now(),
        ]);

        return back()->with('success', 'A new verification code has been sent to your email.');
    }

    public function pendingApproval()
    {
        $userId = session('pending_verification_user_id');
        $user   = User::find($userId);

        if (!$user || !$user->email_verified_at) {
            return redirect()->route('login');
        }

        return view('auth.pending-approval', compact('user'));
    }

    // -------------------------------------------------------------------------
    // Reusable — also called from RegisterController
    // -------------------------------------------------------------------------

    public function sendVerificationEmail(User $user, string $code): void
    {
        Mail::send([], [], function ($message) use ($user, $code) {
            $message
                ->to($user->email, $user->first_name . ' ' . $user->last_name)
                ->subject('Your Kirkwood Help Verification Code')
                ->html($this->buildEmailHtml($user, $code));
        });
    }

    private function buildEmailHtml(User $user, string $code): string
    {
        $year = date('Y');
        return "
        <!DOCTYPE html>
        <html>
        <body style='font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 40px 0;'>
            <div style='max-width: 480px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);'>

                <div style='background: #2563eb; padding: 32px; text-align: center;'>
                    <h1 style='color: #ffffff; margin: 0; font-size: 22px;'>Kirkwood Help System</h1>
                    <p style='color: #bfdbfe; margin: 8px 0 0; font-size: 14px;'>NextStudent</p>
                </div>

                <div style='padding: 32px;'>
                    <p style='color: #374151; font-size: 16px; margin: 0 0 8px;'>Hi {$user->first_name},</p>
                    <p style='color: #6b7280; font-size: 14px; margin: 0 0 24px;'>
                        Use the code below to verify your email address and activate your account.
                    </p>

                    <div style='background: #f9fafb; border: 2px dashed #2563eb; border-radius: 8px; padding: 24px; text-align: center; margin-bottom: 24px;'>
                        <p style='color: #6b7280; font-size: 11px; margin: 0 0 8px; text-transform: uppercase; letter-spacing: 2px;'>Verification Code</p>
                        <p style='color: #1d4ed8; font-size: 40px; font-weight: bold; letter-spacing: 10px; margin: 0;'>{$code}</p>
                    </div>

                    <p style='color: #9ca3af; font-size: 12px; text-align: center; margin: 0;'>
                        ⏰ This code expires in 24 hours.<br>
                        If you did not register for Kirkwood Help, you can safely ignore this email.
                    </p>
                </div>

                <div style='background: #f9fafb; padding: 16px; text-align: center; border-top: 1px solid #e5e7eb;'>
                    <p style='color: #9ca3af; font-size: 11px; margin: 0;'>© {$year} NextStudent Help System</p>
                </div>
            </div>
        </body>
        </html>";
    }
}
