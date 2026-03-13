<?php

namespace App\Http\Controllers;

use App\Models\HelpRequest;
use App\Models\ClassSection;
use App\Services\RequestAssignmentService;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HelpRequestController extends Controller
{
    public function __construct(
        protected RequestAssignmentService $assignmentService,
        protected TwilioService $twilio,
    ) {}

    // -------------------------------------------------------------------------
    // Student: Create request
    // -------------------------------------------------------------------------

    public function create()
    {
        $user = Auth::user();

        // Enforce one active request at a time
        $existing = HelpRequest::where('student_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return redirect()->route('student.dashboard')
                ->with('warning', 'You already have an active help request. Please cancel it before submitting a new one.');
        }

        // Get active sessions this student is enrolled in
        $sessions = $user->enrolledSessions()
            ->where('is_active', true)
            ->with('instructor')
            ->get();

        if ($sessions->isEmpty()) {
            return redirect()->route('student.dashboard')
                ->with('warning', 'You are not enrolled in any active class sections. Please ask your instructor to add you.');
        }

        return view('help-requests.create', compact('sessions'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // One-request guard
        $existing = HelpRequest::where('student_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return redirect()->route('student.dashboard')
                ->with('warning', 'You already have an active help request.');
        }

        $validated = $request->validate([
            'class_session_id' => 'required|exists:class_sessions,id',
            'title'            => 'required|string|max:255',
            'description'      => 'required|string|max:2000',
            'location'         => 'required|string|max:255',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
        ]);

        // Confirm student is enrolled in the chosen session
        $session = ClassSection::findOrFail($validated['class_session_id']);
        $enrolled = $session->students()->where('student_id', $user->id)->exists();

        if (!$enrolled) {
            return back()->withErrors(['class_section_id' => 'You are not enrolled in this section.']);
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('request-images', 'public');
        }

        $helpRequest = HelpRequest::create([
            'student_id'       => $user->id,
            'class_section_id' => $validated['class_section_id'],
            'title'            => $validated['title'],
            'description'      => $validated['description'],
            'location'         => $validated['location'],
            'image'            => $imagePath,
            'status'           => 'pending',
        ]);

        // Assign instructor from session and fire SMS
        $this->assignmentService->assignInstructor($helpRequest);

        return redirect()->route('student.dashboard')
            ->with('success', 'Help request submitted! Your instructor has been notified.');
    }

    // -------------------------------------------------------------------------
    // Shared: View request
    // -------------------------------------------------------------------------

    public function show(HelpRequest $helpRequest)
    {
        $this->authorizeAccess($helpRequest);

        $helpRequest->load([
            'student',
            'assignedInstructor',
            'classSession.instructor',
            'comments.user',
        ]);

        return view('help-requests.show', compact('helpRequest'));
    }

    // -------------------------------------------------------------------------
    // Student: Edit request (pending only)
    // -------------------------------------------------------------------------

    public function edit(HelpRequest $helpRequest)
    {
        $this->authorizeStudentOwns($helpRequest);

        if ($helpRequest->status !== 'pending') {
            return back()->with('error', 'You can only edit a pending request.');
        }

        $sessions = Auth::user()->enrolledSessions()
            ->where('is_active', true)
            ->with('instructor')
            ->get();

        return view('help-requests.edit', compact('helpRequest', 'sessions'));
    }

    public function update(Request $request, HelpRequest $helpRequest)
    {
        $this->authorizeStudentOwns($helpRequest);

        if ($helpRequest->status !== 'pending') {
            return back()->with('error', 'You can only edit a pending request.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'location'    => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
        ]);

        // Replace image if new one uploaded
        if ($request->hasFile('image')) {
            if ($helpRequest->image) {
                Storage::disk('public')->delete($helpRequest->image);
            }
            $validated['image'] = $request->file('image')->store('request-images', 'public');
        }

        $helpRequest->update($validated);

        return redirect()->route('help-requests.show', $helpRequest)
            ->with('success', 'Request updated successfully.');
    }

    // -------------------------------------------------------------------------
    // Student: Cancel request
    // -------------------------------------------------------------------------

    public function cancelForm(HelpRequest $helpRequest)
    {
        $this->authorizeStudentOwns($helpRequest);

        if ($helpRequest->status !== 'pending') {
            return back()->with('error', 'This request cannot be cancelled.');
        }

        return view('help-requests.cancel', compact('helpRequest'));
    }

    public function cancel(Request $request, HelpRequest $helpRequest)
    {
        $this->authorizeStudentOwns($helpRequest);

        if ($helpRequest->status !== 'pending') {
            return back()->with('error', 'This request cannot be cancelled.');
        }

        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        $helpRequest->update([
            'status'              => 'cancelled',
            'cancelled_at'        => now(),
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        // Notify instructor via SMS
        $instructor = $helpRequest->assignedInstructor;
        if ($instructor && $instructor->phone_number) {
            $this->twilio->notifyInstructorRequestCancelledByStudent(
                $instructor->phone_number,
                Auth::user()->fullName(),
            );
        }

        return redirect()->route('student.dashboard')
            ->with('success', 'Your help request has been cancelled.');
    }

    // -------------------------------------------------------------------------
    // Instructor: Mark complete / Cancel
    // -------------------------------------------------------------------------

    public function markComplete(Request $request, HelpRequest $helpRequest)
    {
        $this->authorizeInstructorOwns($helpRequest);

        $request->validate([
            'resolution_notes' => 'nullable|string|max:1000',
        ]);

        $helpRequest->update([
            'status'           => 'completed',
            'completed_at'     => now(),
            'resolution_notes' => $request->resolution_notes,
        ]);

        // Notify student
        $student = $helpRequest->student;
        if ($student->phone_number) {
            $this->twilio->notifyStudentRequestCompleted(
                $student->phone_number,
                Auth::user()->fullName(),
            );
        }

        return redirect()->route('instructor.dashboard')
            ->with('success', 'Request marked as complete.');
    }

    public function instructorCancel(Request $request, HelpRequest $helpRequest)
    {
        $this->authorizeInstructorOwns($helpRequest);

        if ($helpRequest->status !== 'pending') {
            return back()->with('error', 'This request cannot be cancelled.');
        }

        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        $helpRequest->update([
            'status'              => 'cancelled',
            'cancelled_at'        => now(),
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        // Notify student
        $student = $helpRequest->student;
        if ($student->phone_number) {
            $this->twilio->notifyStudentRequestCancelledByInstructor(
                $student->phone_number,
                Auth::user()->fullName(),
            );
        }

        return redirect()->route('instructor.dashboard')
            ->with('success', 'Request cancelled.');
    }

    // -------------------------------------------------------------------------
    // Comments polling endpoint (near real-time)
    // -------------------------------------------------------------------------

    public function pollComments(HelpRequest $helpRequest)
    {
        $this->authorizeAccess($helpRequest);

        $comments = $helpRequest->comments()
            ->with('user:id,first_name,last_name,role,profile_photo')
            ->get()
            ->map(fn($c) => [
                'id'         => $c->id,
                'message'    => $c->message,
                'user'       => [
                    'id'        => $c->user->id,
                    'name'      => $c->user->fullName(),
                    'role'      => $c->user->role,
                    'is_me'     => $c->user_id === Auth::id(),
                    'avatar'    => $c->user->profile_photo
                        ? asset('storage/' . $c->user->profile_photo)
                        : null,
                ],
                'created_at' => $c->created_at->diffForHumans(),
                'raw_time'   => $c->created_at->toISOString(),
            ]);

        return response()->json($comments);
    }

    // -------------------------------------------------------------------------
    // Auth helpers
    // -------------------------------------------------------------------------

    private function authorizeAccess(HelpRequest $helpRequest): void
    {
        $user = Auth::user();
        $allowed = $user->role === 'admin'
            || $helpRequest->student_id === $user->id
            || $helpRequest->assigned_instructor_id === $user->id;

        abort_if(!$allowed, 403);
    }

    private function authorizeStudentOwns(HelpRequest $helpRequest): void
    {
        abort_if($helpRequest->student_id !== Auth::id(), 403);
    }

    private function authorizeInstructorOwns(HelpRequest $helpRequest): void
    {
        abort_if($helpRequest->assigned_instructor_id !== Auth::id(), 403);
    }
}
