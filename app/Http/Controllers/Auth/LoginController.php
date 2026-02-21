<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

                // Store user ID in session in case the notice view needs it
                // (e.g., for a manual "Resend Email" button)
                session(['pending_verification_user_id' => $user->id]);

                return redirect()->route('verification.notice')
                    ->with('error', 'You must verify your email address before logging in. Please check your inbox for the code sent during registration.');
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
}
