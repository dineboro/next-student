<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserApprovalController extends Controller
{
    public function __construct(protected TwilioService $twilio) {}

    /**
     * List instructors pending approval (email already verified).
     */
    public function pending()
    {
        $users = User::where('role', 'instructor')
            ->where('approval_status', 'pending')
            ->whereNotNull('email_verified_at')
            ->latest()
            ->paginate(20);

        return view('admin.users.pending', compact('users'));
    }

    public function approve(User $user)
    {
        $user->update([
            'approval_status' => 'approved',
            'approved_at'     => now(),
            'approved_by'     => Auth::id(),
        ]);

        // SMS the instructor
        if ($user->phone_number) {
            $this->twilio->send(
                $user->phone_number,
                "NextStudent Help System: Your instructor account has been approved! You can now log in at " . config('app.url')
            );
        }

        $this->sendApprovalEmail($user);

        return back()->with('success', "{$user->fullName()} has been approved as an instructor.");
    }

    public function reject(Request $request, User $user)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $user->update([
            'approval_status'  => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'approved_by'      => Auth::id(),
        ]);

        $this->sendRejectionEmail($user, $validated['rejection_reason']);

        return back()->with('success', "{$user->fullName()} has been rejected.");
    }

    private function sendApprovalEmail(User $user): void
    {
        $loginUrl = route('login');
        $year     = date('Y');

        $html = "
        <!DOCTYPE html>
        <html>
        <body style='font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 40px 0;'>
            <div style='max-width: 480px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);'>
                <div style='background: #2563eb; padding: 32px; text-align: center;'>
                    <h1 style='color: #ffffff; margin: 0; font-size: 22px;'>NextStudent Help System</h1>
                    <p style='color: #bfdbfe; margin: 8px 0 0; font-size: 14px;'>Instructor Account Approved</p>
                </div>
                <div style='padding: 32px;'>
                    <p style='color: #374151; font-size: 16px; margin: 0 0 8px;'>Hi {$user->first_name},</p>
                    <p style='color: #6b7280; font-size: 14px; margin: 0 0 24px;'>
                        Great news! Your instructor account has been reviewed and <strong style='color: #16a34a;'>approved</strong>.
                        You can now log in and start using the NextStudent Help System.
                    </p>
                    <div style='text-align: center; margin-bottom: 24px;'>
                        <a href='{$loginUrl}'
                           style='display: inline-block; background: #2563eb; color: #ffffff; font-size: 15px; font-weight: 600;
                                  padding: 12px 32px; border-radius: 8px; text-decoration: none;'>
                            Log In Now
                        </a>
                    </div>
                    <p style='color: #9ca3af; font-size: 12px; text-align: center; margin: 0;'>
                        If you have any questions, please contact support.
                    </p>
                </div>
                <div style='background: #f9fafb; padding: 16px; text-align: center; border-top: 1px solid #e5e7eb;'>
                    <p style='color: #9ca3af; font-size: 11px; margin: 0;'>© {$year} NextStudent Help System</p>
                </div>
            </div>
        </body>
        </html>";

        Mail::send([], [], function ($message) use ($user, $html) {
            $message
                ->to($user->email, $user->first_name . ' ' . $user->last_name)
                ->subject('Your Instructor Account Has Been Approved')
                ->html($html);
        });
    }

    private function sendRejectionEmail(User $user, string $reason): void
    {
        $year = date('Y');

        $html = "
        <!DOCTYPE html>
        <html>
        <body style='font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 40px 0;'>
            <div style='max-width: 480px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);'>
                <div style='background: #2563eb; padding: 32px; text-align: center;'>
                    <h1 style='color: #ffffff; margin: 0; font-size: 22px;'>NextStudent Help System</h1>
                    <p style='color: #bfdbfe; margin: 8px 0 0; font-size: 14px;'>Instructor Account Update</p>
                </div>
                <div style='padding: 32px;'>
                    <p style='color: #374151; font-size: 16px; margin: 0 0 8px;'>Hi {$user->first_name},</p>
                    <p style='color: #6b7280; font-size: 14px; margin: 0 0 16px;'>
                        After reviewing your instructor account application, we were unable to approve it at this time.
                    </p>
                    <div style='background: #fef2f2; border-left: 4px solid #ef4444; border-radius: 4px; padding: 16px; margin-bottom: 24px;'>
                        <p style='color: #991b1b; font-size: 13px; font-weight: 600; margin: 0 0 4px;'>Reason:</p>
                        <p style='color: #7f1d1d; font-size: 13px; margin: 0;'>{$reason}</p>
                    </div>
                    <p style='color: #9ca3af; font-size: 12px; text-align: center; margin: 0;'>
                        If you believe this is an error, please contact support.
                    </p>
                </div>
                <div style='background: #f9fafb; padding: 16px; text-align: center; border-top: 1px solid #e5e7eb;'>
                    <p style='color: #9ca3af; font-size: 11px; margin: 0;'>© {$year} NextStudent Help System</p>
                </div>
            </div>
        </body>
        </html>";

        Mail::send([], [], function ($message) use ($user, $html) {
            $message
                ->to($user->email, $user->first_name . ' ' . $user->last_name)
                ->subject('Update on Your Instructor Account Application')
                ->html($html);
        });
    }
}
