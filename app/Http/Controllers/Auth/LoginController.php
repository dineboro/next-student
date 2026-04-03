<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    private const MAX_ATTEMPTS = 5;
    private const LOCKOUT_SECONDS = 900; // 15 minutes

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $throttleKey = Str::lower($request->email) . '|' . $request->ip();

        // Check lockout
        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);

            throw ValidationException::withMessages([
                'email' => "Too many failed login attempts. Please try again in {$minutes} minute(s).",
            ]);
        }

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if email is verified
            if (!$user->email_verified_at) {
                Auth::logout();
                session(['pending_verification_user_id' => $user->id]);

                return redirect()->route('verification.notice')
                    ->with('error', 'You must verify your email address before logging in.');
            }

            // Check if account is approved
            if ($user->approval_status !== 'approved') {
                Auth::logout();
                session(['pending_verification_user_id' => $user->id]);

                return redirect()->route('verification.pending-approval')
                    ->with('info', 'Your account is pending admin approval.');
            }

            // Redirect based on role
            return match($user->role) {
                'instructor' => redirect()->intended(route('instructor.dashboard')),
                'admin'      => redirect()->intended(route('admin.dashboard')),
                default      => redirect()->intended(route('student.dashboard')),
            };
        }

        // Increment failed attempts
        RateLimiter::hit($throttleKey, self::LOCKOUT_SECONDS);
        $remaining = self::MAX_ATTEMPTS - RateLimiter::attempts($throttleKey);

        throw ValidationException::withMessages([
            'email' => $remaining > 0
                ? "Invalid credentials. {$remaining} attempt(s) remaining before your account is temporarily locked."
                : 'Too many failed login attempts. Please try again in 15 minutes.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
