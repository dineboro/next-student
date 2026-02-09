<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\HelpRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get assigned requests
        $assignedRequests = HelpRequest::with(['student', 'vehicle', 'bay', 'category'])
            ->where('assigned_instructor_id', $user->id)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->orderBy('priority_level', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        // Get pending requests in queue
        $pendingRequests = HelpRequest::with(['student', 'vehicle', 'bay', 'category', 'queuePosition'])
            ->where('status', 'pending')
            ->whereHas('student', function($query) use ($user) {
                $query->where('school_id', $user->school_id);
            })
            ->orderBy('priority_level', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        // Statistics
        $stats = [
            'completed_today' => HelpRequest::where('assigned_instructor_id', $user->id)
                ->where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),

            'active_requests' => $assignedRequests->count(),

            'average_rating' => DB::table('ratings')
                ->where('instructor_id', $user->id)
                ->avg('rating'),

            'total_completed' => HelpRequest::where('assigned_instructor_id', $user->id)
                ->where('status', 'completed')
                ->count(),
        ];

        return view('instructor.dashboard', compact(
            'assignedRequests',
            'pendingRequests',
            'stats'
        ));
    }

    public function toggleAvailability()
    {
        $user = Auth::user();
        $user->is_available = !$user->is_available;
        $user->save();

        $status = $user->is_available ? 'available' : 'unavailable';
        return back()->with('success', "You are now marked as {$status}");
    }

    public function activeRequests()
    {
        $user = Auth::user();

        $requests = HelpRequest::with(['student', 'vehicle', 'bay', 'category'])
            ->where('assigned_instructor_id', $user->id)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->latest()
            ->paginate(10);

        return view('instructor.active-requests', compact('requests'));
    }

    public function history()
    {
        $user = Auth::user();

        $requests = HelpRequest::with(['student', 'vehicle', 'category', 'rating'])
            ->where('assigned_instructor_id', $user->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->latest('completed_at')
            ->paginate(15);

        return view('instructor.history', compact('requests'));
    }
}
