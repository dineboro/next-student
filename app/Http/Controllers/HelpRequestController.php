<?php

namespace App\Http\Controllers;

use App\Models\HelpRequest;
use App\Models\Vehicle;
use App\Models\Bay;
use App\Models\RequestCategory;
use App\Services\RequestAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HelpRequestController extends Controller
{
    use AuthorizesRequests;
    protected $assignmentService;

    public function __construct(RequestAssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'student') {
            return redirect()->route('student.dashboard');
        }

        return redirect()->route('instructor.dashboard');
    }

    public function create()
    {
        $this->authorize('create', HelpRequest::class);

        $user = Auth::user();

        // Check if student already has an active request
        $activeRequest = HelpRequest::where('student_id', $user->id)
            ->whereIn('status', ['pending', 'assigned', 'in_progress'])
            ->exists();

        if ($activeRequest) {
            return back()->with('error', 'You already have an active request. Please complete or cancel it first.');
        }

        $vehicles = Vehicle::where('school_id', $user->school_id)
            ->where('status', 'available')
            ->get();

        $bays = Bay::where('school_id', $user->school_id)
            ->where('status', 'available')
            ->get();

        $categories = RequestCategory::where('is_active', true)->get();

        return view('help-requests.create', compact('vehicles', 'bays', 'categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', HelpRequest::class);

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'bay_id' => 'nullable|exists:bays,id',
            'category_id' => 'required|exists:request_categories,id',
            'title' => 'nullable|string|max:255',
            'description' => 'required|string|max:1000',
            'priority_level' => 'required|in:low,medium,high,emergency',
        ]);

        $user = Auth::user();

        // Create help request
        $helpRequest = DB::transaction(function () use ($validated, $user) {
            $helpRequest = HelpRequest::create([
                'student_id' => $user->id,
                'vehicle_id' => $validated['vehicle_id'],
                'bay_id' => $validated['bay_id'],
                'category_id' => $validated['category_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'priority_level' => $validated['priority_level'],
                'status' => 'pending',
            ]);

            // Update bay status if selected
            if ($validated['bay_id']) {
                Bay::where('id', $validated['bay_id'])->update(['status' => 'occupied']);
            }

            // Update vehicle status
            Vehicle::where('id', $validated['vehicle_id'])->update(['status' => 'in_use']);

            return $helpRequest;
        });

        // Attempt auto-assignment
        $this->assignmentService->assignInstructor($helpRequest);

        return redirect()->route('student.dashboard')
            ->with('success', 'Help request created successfully! An instructor will be assigned shortly.');
    }

    public function show(HelpRequest $helpRequest)
    {
        $this->authorize('view', $helpRequest);

        $helpRequest->load([
            'student',
            'assignedInstructor',
            'vehicle',
            'bay',
            'category',
            'comments.user',
            'attachments',
            'rating',
            'queuePosition'
        ]);

        return view('help-requests.show', compact('helpRequest'));
    }

    public function update(Request $request, HelpRequest $helpRequest)
    {
        $this->authorize('update', $helpRequest);

        $validated = $request->validate([
            'status' => 'required|in:assigned,in_progress,completed,cancelled',
            'resolution_notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($helpRequest, $validated) {
            $oldStatus = $helpRequest->status;

            $helpRequest->update($validated);

            // Update timestamps based on status
            if ($validated['status'] === 'in_progress' && $oldStatus !== 'in_progress') {
                $helpRequest->update(['started_at' => now()]);
            }

            if ($validated['status'] === 'completed') {
                $helpRequest->update(['completed_at' => now()]);

                // Free up resources
                if ($helpRequest->bay_id) {
                    Bay::where('id', $helpRequest->bay_id)->update(['status' => 'available']);
                }
                Vehicle::where('id', $helpRequest->vehicle_id)->update(['status' => 'available']);
            }

            if ($validated['status'] === 'cancelled') {
                $helpRequest->update(['cancelled_at' => now()]);

                // Free up resources
                if ($helpRequest->bay_id) {
                    Bay::where('id', $helpRequest->bay_id)->update(['status' => 'available']);
                }
                Vehicle::where('id', $helpRequest->vehicle_id)->update(['status' => 'available']);
            }

            // Log activity
            $helpRequest->activityLogs()->create([
                'user_id' => Auth::id(),
                'action' => 'status_updated',
                'description' => "Status changed from {$oldStatus} to {$validated['status']}",
                'old_values' => ['status' => $oldStatus],
                'new_values' => ['status' => $validated['status']],
            ]);
        });

        return back()->with('success', 'Request updated successfully!');
    }

    public function destroy(HelpRequest $helpRequest)
    {
        $this->authorize('delete', $helpRequest);

        DB::transaction(function () use ($helpRequest) {
            // Free up resources
            if ($helpRequest->bay_id) {
                Bay::where('id', $helpRequest->bay_id)->update(['status' => 'available']);
            }
            Vehicle::where('id', $helpRequest->vehicle_id)->update(['status' => 'available']);

            $helpRequest->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);
        });

        return redirect()->route('student.dashboard')
            ->with('success', 'Request cancelled successfully!');
    }
}
