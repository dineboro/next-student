<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use App\Models\SchoolRegistrationRequest;
use App\Models\HelpRequest;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $stats = [
            'pending_users' => User::where('approval_status', 'pending')->count(),
            'pending_schools' => 0, // Default to 0
            'total_users' => User::where('approval_status', 'approved')->count(),
            'total_schools' => School::where('approval_status', 'approved')->count(),
            'active_requests' => HelpRequest::whereIn('status', ['pending', 'assigned', 'in_progress'])->count(),
        ];

        //  Safely try to get pending schools (in case table doesn't exist)
        try {
            $stats['pending_schools'] = SchoolRegistrationRequest::where('status', 'pending')->count();
        } catch (\Exception $e) {
            \Log::warning('school_registration_requests table not found');
        }

        $recentUsers = User::where('approval_status', 'pending')
            ->with('school')
            ->latest()
            ->limit(5)
            ->get();

        //  Safely try to get recent schools
        $recentSchools = collect([]);
        try {
            $recentSchools = SchoolRegistrationRequest::where('status', 'pending')
                ->latest()
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            \Log::warning('school_registration_requests table not found');
        }

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentSchools'));
    }
}
