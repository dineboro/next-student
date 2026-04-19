<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpRequest;
use App\Models\User;

class UserController extends Controller
{
    public function students()
    {
        $students = User::where('role', 'student')
            ->where('approval_status', 'approved')
            ->latest()
            ->paginate(20);

        return view('admin.students', compact('students'));
    }

    public function instructors()
    {
        $instructors = User::where('role', 'instructor')
            ->where('approval_status', 'approved')
            ->latest()
            ->paginate(20);

        return view('admin.instructors', compact('instructors'));
    }

    public function deactivate(User $user)
    {
        abort_if($user->role !== 'instructor', 403);

        // Deactivate all their class sections
        $user->classSections()->update(['is_active' => false]);

        // Cancel all pending requests assigned to this instructor
        HelpRequest::where('assigned_instructor_id', $user->id)
            ->where('status', 'pending')
            ->update([
                'status'              => 'cancelled',
                'cancelled_at'        => now(),
                'cancellation_reason' => 'Your assigned instructor is no longer available. Please contact your department for further assistance.',
            ]);

        // Block the instructor from logging in
        $user->update(['approval_status' => 'deactivated']);

        return back()->with('success', "{$user->fullName()} has been deactivated. Their sections are closed and pending requests have been cancelled.");
    }
}
