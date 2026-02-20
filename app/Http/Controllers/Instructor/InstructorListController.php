<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class InstructorListController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:instructor']);
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Build query for instructors at the same school
        $query = User::where('school_id', $user->school_id)
            ->where('role', 'instructor')
            ->where('approval_status', 'approved')
            ->with(['school'])
            ->withCount(['helpRequestsAsInstructor as active_requests_count' => function ($query) {
                $query->whereIn('status', ['assigned', 'in_progress']);
            }]);

        // Filter by availability
        if ($request->has('availability')) {
            if ($request->availability === 'available') {
                $query->where('is_available', true);
            } elseif ($request->availability === 'unavailable') {
                $query->where('is_available', false);
            }
        }

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'ilike', "%{$search}%")
                    ->orWhere('last_name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'name');
        switch ($sortBy) {
            case 'name':
                $query->orderBy('first_name')->orderBy('last_name');
                break;
            case 'availability':
                $query->orderByDesc('is_available');
                break;
            case 'workload':
                $query->orderBy('active_requests_count', 'asc');
                break;
        }

        $instructors = $query->paginate(12);

        // Get stats
        $stats = [
            'total' => User::where('school_id', $user->school_id)
                ->where('role', 'instructor')
                ->where('approval_status', 'approved')
                ->count(),
            'available' => User::where('school_id', $user->school_id)
                ->where('role', 'instructor')
                ->where('approval_status', 'approved')
                ->where('is_available', true)
                ->count(),
            'busy' => User::where('school_id', $user->school_id)
                ->where('role', 'instructor')
                ->where('approval_status', 'approved')
                ->where('is_available', false)
                ->count(),
        ];

        return view('instructor.instructors-list', compact('instructors', 'stats'));
    }

    public function show(User $instructor)
    {
        $user = Auth::user();

        // Ensure it's an instructor at the same school
        if ($instructor->role !== 'instructor' || $instructor->school_id !== $user->school_id) {
            abort(404);
        }

        // Get instructor stats
        $stats = [
            'total_completed' => $instructor->helpRequestsAsInstructor()
                ->where('status', 'completed')
                ->count(),

            'active_requests' => $instructor->helpRequestsAsInstructor()
                ->whereIn('status', ['assigned', 'in_progress'])
                ->count(),

            'completed_today' => $instructor->helpRequestsAsInstructor()
                ->where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),

            'average_rating' => DB::table('ratings')
                ->where('instructor_id', $instructor->id)
                ->avg('rating'),

            'total_ratings' => DB::table('ratings')
                ->where('instructor_id', $instructor->id)
                ->count(),
        ];

        // Get recent completed requests
        $recentRequests = $instructor->helpRequestsAsInstructor()
            ->with(['student', 'category', 'rating'])
            ->where('status', 'completed')
            ->latest('completed_at')
            ->limit(5)
            ->get();

        return view('instructor.instructor-profile', compact('instructor', 'stats', 'recentRequests'));
    }
}
