<?php

namespace App\Policies;

use App\Models\HelpRequest;
use App\Models\User;

class HelpRequestPolicy
{
    /**
     * Determine if the user can view any help requests.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view requests
    }

    /**
     * Determine if the user can view the help request.
     */
    public function view(User $user, HelpRequest $helpRequest): bool
    {
        // Students can view their own requests
        if ($user->role === 'student' && $helpRequest->student_id === $user->id) {
            return true;
        }

        // Instructors can view requests assigned to them or pending requests at their school
        if ($user->role === 'instructor') {
            return $helpRequest->assigned_instructor_id === $user->id ||
                ($helpRequest->student && $helpRequest->student->school_id === $user->school_id);
        }

        return false;
    }

    /**
     * Determine if the user can create help requests.
     */
    public function create(User $user): bool
    {
        // Only students can create requests
        return $user->role === 'student';
    }

    /**
     * Determine if the user can update the help request.
     */
    public function update(User $user, HelpRequest $helpRequest): bool
    {
        // Only the assigned instructor can update the request
        return $user->role === 'instructor' &&
            $helpRequest->assigned_instructor_id === $user->id;
    }

    /**
     * Determine if the user can delete/cancel the help request.
     */
    public function delete(User $user, HelpRequest $helpRequest): bool
    {
        // Students can cancel their own pending requests
        return $user->role === 'student' &&
            $helpRequest->student_id === $user->id &&
            $helpRequest->status === 'pending';
    }
}
