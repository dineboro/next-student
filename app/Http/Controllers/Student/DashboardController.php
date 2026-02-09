<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\HelpRequest;
use App\Models\Vehicle;
use App\Models\Bay;
use App\Models\RequestCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        // Get active request (if any)
        $activeRequest = HelpRequest::with(['vehicle', 'bay', 'category', 'assignedInstructor'])
            ->where('student_id', $user->id)
            ->whereIn('status', ['pending', 'assigned', 'in_progress'])
            ->latest()
            ->first();

        // Get queue position if request is pending
        $queuePosition = null;
        $estimatedWaitTime = null;
        if ($activeRequest && $activeRequest->status === 'pending') {
            $queuePosition = $activeRequest->queuePosition;
            if ($queuePosition) {
                $estimatedWaitTime = $queuePosition->estimated_wait_minutes;
            }
        }

        // Get request history
        $requestHistory = HelpRequest::with(['vehicle', 'category', 'assignedInstructor'])
            ->where('student_id', $user->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->latest()
            ->limit(5)
            ->get();

        // Get available resources
        $availableVehicles = Vehicle::where('school_id', $user->school_id)
            ->where('status', 'available')
            ->count();

        $availableBays = Bay::where('school_id', $user->school_id)
            ->where('status', 'available')
            ->count();

        $availableInstructors = \App\Models\User::where('school_id', $user->school_id)
            ->where('role', 'instructor')
            ->where('is_available', true)
            ->count();

        return view('student.dashboard', compact(
            'activeRequest',
            'queuePosition',
            'estimatedWaitTime',
            'requestHistory',
            'availableVehicles',
            'availableBays',
            'availableInstructors'
        ));
    }

    public function myRequests()
    {
        $user = Auth::user();

        $requests = HelpRequest::with(['vehicle', 'bay', 'category', 'assignedInstructor', 'rating'])
            ->where('student_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('student.my-requests', compact('requests'));
    }
}
