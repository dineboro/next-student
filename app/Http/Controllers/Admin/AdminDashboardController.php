<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\HelpRequest;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending_instructors' => User::where('role', 'instructor')
                ->where('approval_status', 'pending')
                ->whereNotNull('email_verified_at')
                ->count(),
            'total_students'      => User::where('role', 'student')->where('approval_status', 'approved')->count(),
            'total_instructors'   => User::where('role', 'instructor')->where('approval_status', 'approved')->count(),
            'active_requests'     => HelpRequest::where('status', 'pending')->count(),
            'completed_today'     => HelpRequest::where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),
        ];

        $pendingInstructors = User::where('role', 'instructor')
            ->where('approval_status', 'pending')
            ->whereNotNull('email_verified_at')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingInstructors'));
    }
}
