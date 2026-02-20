<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if email is verified
            if (!$user->email_verified_at) {
                Auth::logout();

                // Generate NEW verification code
                $verificationCode = strtoupper(Str::random(6));

                $user->update([
                    'verification_code' => $verificationCode,
                    'verification_code_expires_at' => now()->addHours(24),
                ]);

                // Send NEW verification email
                $this->sendVerificationEmail($user, $verificationCode);

                session(['pending_verification_user_id' => $user->id]);

                return redirect()->route('verification.notice')
                    ->with('success', 'A new verification code has been sent to your email.');
            }

            // Check if account is approved
            if ($user->approval_status !== 'approved') {
                Auth::logout();

                session(['pending_verification_user_id' => $user->id]);

                return redirect()->route('verification.pending-approval')
                    ->with('info', 'Your account is pending admin approval.');
            }

            // Redirect based on role
            if ($user->role === 'instructor') {
                return redirect()->intended(route('instructor.dashboard'));
            } elseif ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } else {
                return redirect()->intended(route('student.dashboard'));
            }
        }

        throw ValidationException::withMessages([
            'email' => __('The provided credentials do not match our records.'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }

    /**
     * Send verification email
     */
    protected function sendVerificationEmail($user, $code)
    {
        try {
            \Mail::to($user->email)->send(new \App\Mail\VerificationCodeMail($user, $code));
            \Log::info("Verification email sent successfully to {$user->email}");
        } catch (\Exception $e) {
            \Log::error("Failed to send verification email to {$user->email}: " . $e->getMessage());
            \Log::info("Verification code for {$user->email}: {$code}");
        }
    }
}
