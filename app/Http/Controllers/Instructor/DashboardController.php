<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\HelpRequest;
use App\Models\ClassSection;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:instructor']);
    }

    public function index()
    {
        $user = Auth::user();

        $pendingRequests = HelpRequest::where('assigned_instructor_id', $user->id)
            ->where('status', 'pending')
            ->with(['student', 'classSection'])
            ->latest()
            ->get();

        $stats = [
            'pending_requests'  => $pendingRequests->count(),
            'completed_today'   => HelpRequest::where('assigned_instructor_id', $user->id)
                ->where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),
            'total_completed'   => HelpRequest::where('assigned_instructor_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            'active_sessions'   => ClassSection::where('instructor_id', $user->id)
                ->where('is_active', true)
                ->count(),
        ];

        return view('instructor.dashboard', compact('pendingRequests', 'stats'));
    }

    public function history()
    {
        $user = Auth::user();

        $requests = HelpRequest::where('assigned_instructor_id', $user->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->with(['student', 'classSection'])
            ->latest()
            ->paginate(20);

        return view('instructor.history', compact('requests'));
    }
}
