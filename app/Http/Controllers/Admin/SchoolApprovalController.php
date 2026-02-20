<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolRegistrationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SchoolApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function pendingRequests()
    {
        $requests = SchoolRegistrationRequest::where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.schools.pending-requests', compact('requests'));
    }

    public function approve(SchoolRegistrationRequest $request)
    {
        DB::transaction(function () use ($request) {
            // Create the school
            $school = School::create([
                'school_name' => $request->school_name,
                'school_domain' => $request->school_domain,
                'address' => $request->address,
                'contact_info' => $request->contact_info,
                'registration_id' => $request->registration_id,
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);

            // Update request status
            $request->update([
                'status' => 'approved',
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
            ]);
        });

        // TODO: Send approval email to requester

        return back()->with('success', "School {$request->school_name} has been approved!");
    }

    public function reject(Request $rejectionRequest, SchoolRegistrationRequest $schoolRequest)
    {
        $validated = $rejectionRequest->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $schoolRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        // TODO: Send rejection email to requester with reason

        return back()->with('success', "School request has been rejected.");
    }

}
