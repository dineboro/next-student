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
            'pending_schools' => SchoolRegistrationRequest::where('status', 'pending')->count(),
            'total_users' => User::where('approval_status', 'approved')->count(),
            'total_schools' => School::where('approval_status', 'approved')->count(),
            'active_requests' => HelpRequest::whereIn('status', ['pending', 'assigned', 'in_progress'])->count(),
        ];

        $recentUsers = User::where('approval_status', 'pending')
            ->with('school')
            ->latest()
            ->limit(5)
            ->get();

        $recentSchools = SchoolRegistrationRequest::where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentSchools'));
    }

}
