<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function pending()
    {
        $users = User::where('approval_status', 'pending')
            ->where('email_verified_at', '!=', null)
            ->with('school')
            ->latest()
            ->paginate(20);

        return view('admin.users.pending', compact('users'));
    }

    public function approve(User $user)
    {
        $user->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        // TODO: Send approval email to user

        return back()->with('success', "User {$user->first_name} {$user->last_name} has been approved!");
    }

    public function reject(Request $request, User $user)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $user->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'approved_by' => Auth::id(),
        ]);

        // TODO: Send rejection email to user with reason

        return back()->with('success', "User {$user->first_name} {$user->last_name} has been rejected.");
    }
}
