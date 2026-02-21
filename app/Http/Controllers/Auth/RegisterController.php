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

        // Extract full domain from email
        $fullDomain = substr(strrchr($validated['email'], "@"), 1);

        // Extract root domain (handles subdomains)
        $emailDomain = $this->extractRootDomain($fullDomain);

        // Try to find school with exact match first
        $school = School::where('school_domain', $fullDomain)
            ->where('approval_status', 'approved')
            ->first();

        // If not found, try with root domain
        if (!$school) {
            $school = School::where('school_domain', $emailDomain)
                ->where('approval_status', 'approved')
                ->first();
        }

        // If still not found, check if school exists but not approved
        if (!$school) {
            $pendingSchool = School::where(function($query) use ($fullDomain, $emailDomain) {
                $query->where('school_domain', $fullDomain)
                    ->orWhere('school_domain', $emailDomain);
            })->whereIn('approval_status', ['pending', 'rejected'])->first();

            if ($pendingSchool) {
                return back()->withErrors([
                    'email' => 'Your school is currently pending approval. Please wait for administrator approval or contact support.'
                ])->withInput();
            }

            // School doesn't exist at all
            return back()->withErrors([
                'email' => "No school found for domain '@{$emailDomain}'. Please ask your school administrator to register at: " . route('school-registration.create')
            ])->withInput();
        }

        // Rest of your registration code...
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
            'approval_status' => 'approved',
            'approved_at' => now(),
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
    protected function extractRootDomain($domain)
    {
        // Remove any protocol if present
        $domain = str_replace(['http://', 'https://', 'www.'], '', $domain);

        // Split by dots
        $parts = explode('.', $domain);

        // If only 2 parts (e.g., kirkwood.edu), return as is
        if (count($parts) <= 2) {
            return $domain;
        }

        // Get last 2 parts (root domain)
        // student.kirkwood.edu -> kirkwood.edu
        // mail.google.com -> google.com
        return $parts[count($parts) - 2] . '.' . $parts[count($parts) - 1];
    }

    protected function sendVerificationEmail($user, $code)
    {
        try {
            \Mail::to($user->email)->send(new \App\Mail\VerificationCodeMail($user, $code));

            // Log success
            \Log::info("Verification email sent successfully to {$user->email}");
        } catch (\Exception $e) {
            // Log error but don't fail registration
            \Log::error("Failed to send verification email to {$user->email}: " . $e->getMessage());

            // Also log the code so admin can help user if needed
            \Log::info("Verification code for {$user->email}: {$code}");
        }
    }
}
