<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
//use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    //
    public function showRegistrationForm(){
    $schools = School::all();
    return view('auth.register', compact('schools'));
}

    public function register(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:student,instructor',
        ]);

        // Generate student_id or instructor_id
        $idPrefix = $validated['role'] === 'student' ? 'STU-' . date('Y') . '-' : 'INST-';
        $lastUser = User::where('role', $validated['role'])
            ->where('school_id', $validated['school_id'])
            ->latest('id')
            ->first();

        $nextNumber = $lastUser ? (int)substr($lastUser->{$validated['role'] . '_id'}, -3) + 1 : 1;
        $uniqueId = $idPrefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $user = User::create([
            'school_id' => $validated['school_id'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_available' => $validated['role'] === 'instructor' ? false : false,
            $validated['role'] . '_id' => $uniqueId,
        ]);

        // Assign Spatie role
        $user->assignRole($validated['role']);

        // Create notification preferences
        $user->notificationPreferences()->create([
            'sms_enabled' => true,
            'email_enabled' => true,
            'in_app_enabled' => true,
            'notify_on_request_assigned' => true,
            'notify_on_request_completed' => true,
            'notify_on_new_comment' => true,
            'notify_on_status_change' => true,
        ]);

        Auth::login($user);

        return redirect()->route($validated['role'] . '.dashboard')
            ->with('success', 'Registration successful! Welcome aboard.');
    }
}
