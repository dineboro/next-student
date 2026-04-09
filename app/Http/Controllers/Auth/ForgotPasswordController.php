<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ForgotPasswordController extends Controller
{
    // -------------------------------------------------------------------------
    // Step 1 — Enter email
    // -------------------------------------------------------------------------

    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Don't reveal whether the email exists — always show the same message
        $user = User::where('email', $request->email)->first();

        if ($user && $user->email_verified_at) {
            $code = strtoupper(Str::random(6));

            $user->update([
                'password_reset_token'      => $code,
                'password_reset_expires_at' => now()->addMinutes(15),
            ]);

            $this->sendResetEmail($user, $code);

            session(['password_reset_user_id' => $user->id]);
        }

        return redirect()->route('password.reset.form')
            ->with('info', 'If that email is registered and verified, a reset code has been sent.');
    }

    // -------------------------------------------------------------------------
    // Step 2 — Enter code + new password
    // -------------------------------------------------------------------------

    public function showResetForm()
    {
        return view('auth.reset-password');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'code'                  => 'required|string',
            'password'              => ['required', 'confirmed', Password::min(8)],
        ]);

        $userId = session('password_reset_user_id');
        $user   = $userId ? User::find($userId) : null;

        if (!$user) {
            return redirect()->route('password.forgot')
                ->withErrors(['code' => 'Session expired. Please start over.']);
        }

        if (!$user->password_reset_token || $user->password_reset_expires_at < now()) {
            return back()->withErrors(['code' => 'This code has expired. Please request a new one.']);
        }

        if (strtoupper($request->code) !== strtoupper($user->password_reset_token)) {
            return back()->withErrors(['code' => 'Invalid code. Please try again.']);
        }

        $user->update([
            'password'                  => Hash::make($request->password),
            'password_reset_token'      => null,
            'password_reset_expires_at' => null,
        ]);

        session()->forget('password_reset_user_id');

        return redirect()->route('login')
            ->with('success', 'Password reset successfully. You can now log in.');
    }

    // -------------------------------------------------------------------------
    // Email
    // -------------------------------------------------------------------------

    private function sendResetEmail(User $user, string $code): void
    {
        Mail::send([], [], function ($message) use ($user, $code) {
            $message
                ->to($user->email, $user->fullName())
                ->subject('Your Kirkwood Help Password Reset Code')
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
                        We received a request to reset your password. Use the code below — it expires in 15 minutes.
                    </p>
                    <div style='background: #f9fafb; border: 2px dashed #2563eb; border-radius: 8px; padding: 24px; text-align: center; margin-bottom: 24px;'>
                        <p style='color: #6b7280; font-size: 11px; margin: 0 0 8px; text-transform: uppercase; letter-spacing: 2px;'>Reset Code</p>
                        <p style='color: #1d4ed8; font-size: 40px; font-weight: bold; letter-spacing: 10px; margin: 0;'>{$code}</p>
                    </div>
                    <p style='color: #9ca3af; font-size: 12px; text-align: center; margin: 0;'>
                        If you did not request a password reset, you can safely ignore this email.
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
