<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function students()
    {
        $students = User::where('role', 'student')
            ->where('approval_status', 'approved')
            ->latest()
            ->paginate(20);

        return view('admin.students', compact('students'));
    }

    public function instructors()
    {
        $instructors = User::where('role', 'instructor')
            ->where('approval_status', 'approved')
            ->latest()
            ->paginate(20);

        return view('admin.instructors', compact('instructors'));
    }
}
