<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        // Get only approved schools
        $schools = School::where('approval_status', 'approved')->get();

        return view('auth.register', compact('schools'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:student,instructor',
        ]);

        // Extract domain from email
        $emailDomain = substr(strrchr($validated['email'], "@"), 1);

        // Check if school exists with this domain
        $school = School::where('school_domain', $emailDomain)
            ->where('approval_status', 'approved')
            ->first();

        if (!$school) {
            return back()->withErrors([
                'email' => 'No approved school found for this email domain. Please contact your school administrator or register your school first.'
            ])->withInput();
        }

        // Generate verification code
        $verificationCode = strtoupper(Str::random(6));

        // Generate student_id or instructor_id
        $idPrefix = $validated['role'] === 'student' ? 'STU-' . date('Y') . '-' : 'INST-';
        $lastUser = User::where('role', $validated['role'])
            ->where('school_id', $school->id)
            ->latest('id')
            ->first();

        $nextNumber = $lastUser ? (int)substr($lastUser->{$validated['role'] . '_id'}, -3) + 1 : 1;
        $uniqueId = $idPrefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $user = User::create([
            'school_id' => $school->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_available' => false,
            $validated['role'] . '_id' => $uniqueId,
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => now()->addHours(24),
            'approval_status' => 'pending', // Requires admin approval
        ]);

        // Assign Spatie role
        $user->assignRole($validated['role']);

        // Create default user settings
        UserSettings::create([
            'user_id' => $user->id,
        ]);

        // Send verification email
        $this->sendVerificationEmail($user, $verificationCode);

        // Store user ID in session for verification page
        session(['pending_verification_user_id' => $user->id]);

        return redirect()->route('verification.notice')
            ->with('success', 'Registration successful! Please check your email for the verification code.');
    }

    protected function sendVerificationEmail($user, $code)
    {
        // TODO: Implement actual email sending
        // For now, we'll just log it
        \Log::info("Verification code for {$user->email}: {$code}");

        // You would use Laravel Mail here:
        // Mail::to($user->email)->send(new VerificationEmail($code));
    }
}
