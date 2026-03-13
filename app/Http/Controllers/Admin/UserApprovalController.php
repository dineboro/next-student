<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                "Kirkwood Help System: Your instructor account has been approved! You can now log in at " . config('app.url')
            );
        }

        // TODO: Send approval email

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

        // TODO: Send rejection email with reason

        return back()->with('success', "{$user->fullName()} has been rejected.");
    }
}
