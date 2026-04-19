<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\HelpRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:student']);
    }

    public function index()
    {
        $user = Auth::user();

        $activeRequest = HelpRequest::where('student_id', $user->id)
            ->where('status', 'pending')
            ->with(['assignedInstructor', 'classSection'])
            ->first();

        $pastRequests = HelpRequest::where('student_id', $user->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->with(['assignedInstructor', 'classSection'])
            ->latest()
            ->limit(10)
            ->get();

        $enrolledSections = $user->enrolledSections()
            ->where('is_active', true)
            ->with('instructor')
            ->get();

        // Compute queue position: how many pending requests in the same section were submitted before this one
        $queuePosition = null;
        if ($activeRequest) {
            $queuePosition = HelpRequest::where('class_section_id', $activeRequest->class_section_id)
                ->where('status', 'pending')
                ->where(function ($q) use ($activeRequest) {
                    $q->where('created_at', '<', $activeRequest->created_at)
                      ->orWhere(function ($q2) use ($activeRequest) {
                          $q2->where('created_at', $activeRequest->created_at)
                             ->where('id', '<', $activeRequest->id);
                      });
                })
                ->count() + 1;
        }

        return view('student.dashboard', compact('activeRequest', 'pastRequests', 'enrolledSections', 'queuePosition'));
    }

    public function myRequests()
    {
        $user = Auth::user();

        $requests = HelpRequest::where('student_id', $user->id)
            ->with(['assignedInstructor', 'classSection'])
            ->latest()
            ->paginate(15);
//        dd($requests);
        return view('student.my-requests', compact('requests'));
    }
}
