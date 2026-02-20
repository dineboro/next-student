<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\HelpRequest;
use App\Models\Vehicle;
use App\Models\Bay;
use App\Models\RequestCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class RequestManagementController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware(['auth', 'role:student']);
    }

    public function edit(HelpRequest $helpRequest)
    {
        $this->authorize('update', $helpRequest);

        // Only allow editing of pending requests
        if ($helpRequest->status !== 'pending') {
            return back()->with('error', 'You can only edit pending requests.');
        }

        $user = Auth::user();

        $vehicles = Vehicle::where('school_id', $user->school_id)
            ->where('status', 'available')
            ->orWhere('id', $helpRequest->vehicle_id)
            ->get();

        $bays = Bay::where('school_id', $user->school_id)
            ->where('status', 'available')
            ->orWhere('id', $helpRequest->bay_id)
            ->get();

        $categories = RequestCategory::where('is_active', true)->get();

        return view('student.edit-request', compact('helpRequest', 'vehicles', 'bays', 'categories'));
    }

    public function update(Request $request, HelpRequest $helpRequest)
    {
        $this->authorize('update', $helpRequest);

        // Only allow updating of pending requests
        if ($helpRequest->status !== 'pending') {
            return back()->with('error', 'You can only update pending requests.');
        }

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'bay_id' => 'nullable|exists:bays,id',
            'category_id' => 'required|exists:request_categories,id',
            'title' => 'nullable|string|max:255',
            'description' => 'required|string|max:1000',
            'priority_level' => 'required|in:low,medium,high,emergency',
        ]);

        DB::transaction(function () use ($helpRequest, $validated) {
            // Store old values for history
            $oldValues = $helpRequest->only([
                'vehicle_id', 'bay_id', 'category_id',
                'title', 'description', 'priority_level'
            ]);

            // Update bay status if changed
            if ($helpRequest->bay_id !== $validated['bay_id']) {
                // Free old bay
                if ($helpRequest->bay_id) {
                    Bay::where('id', $helpRequest->bay_id)->update(['status' => 'available']);
                }
                // Occupy new bay
                if ($validated['bay_id']) {
                    Bay::where('id', $validated['bay_id'])->update(['status' => 'occupied']);
                }
            }

            // Update vehicle status if changed
            if ($helpRequest->vehicle_id !== $validated['vehicle_id']) {
                // Free old vehicle
                Vehicle::where('id', $helpRequest->vehicle_id)->update(['status' => 'available']);
                // Occupy new vehicle
                Vehicle::where('id', $validated['vehicle_id'])->update(['status' => 'in_use']);
            }

            // Update request
            $helpRequest->update($validated);

            // Log the update
            $helpRequest->activityLogs()->create([
                'user_id' => Auth::id(),
                'action' => 'request_updated',
                'description' => 'Request details updated by student',
                'old_values' => $oldValues,
                'new_values' => $validated,
            ]);
        });

        return redirect()->route('student.dashboard')
            ->with('success', 'Request updated successfully!');
    }

    public function cancelForm(HelpRequest $helpRequest)
    {
        $this->authorize('delete', $helpRequest);

        // Only allow canceling pending requests
        if ($helpRequest->status !== 'pending') {
            return back()->with('error', 'You can only cancel pending requests.');
        }

        return view('student.cancel-request', compact('helpRequest'));
    }

    public function cancel(Request $request, HelpRequest $helpRequest)
    {
        $this->authorize('delete', $helpRequest);

        // Only allow canceling pending requests
        if ($helpRequest->status !== 'pending') {
            return back()->with('error', 'You can only cancel pending requests.');
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        DB::transaction(function () use ($helpRequest, $validated) {
            // Free up resources
            if ($helpRequest->bay_id) {
                Bay::where('id', $helpRequest->bay_id)->update(['status' => 'available']);
            }
            Vehicle::where('id', $helpRequest->vehicle_id)->update(['status' => 'available']);

            // Update request
            $helpRequest->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $validated['cancellation_reason'],
            ]);

            // Remove from queue if exists
            $helpRequest->queuePosition?->delete();

            // Log activity
            $helpRequest->activityLogs()->create([
                'user_id' => Auth::id(),
                'action' => 'request_cancelled',
                'description' => 'Request cancelled by student',
                'new_values' => ['cancellation_reason' => $validated['cancellation_reason']],
            ]);
        });

        return redirect()->route('student.dashboard')
            ->with('success', 'Request cancelled successfully.');
    }

}
