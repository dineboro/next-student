<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    private const ALLOWED_DOMAIN = [
        'kirkwood.edu',
        'student.kirkwood.edu'
    ];

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $role = $request->input('role', 'student');

        // Base validation rules
        $rules = [
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'     => ['required', 'confirmed', Password::min(8)],
            'role'         => 'required|in:student,instructor',
            'phone_number' => 'required|string|max:20',
            'consent'      => 'accepted',
        ];

        if ($role === 'student') {
            $rules['major']     = 'required|string|max:255';
            $rules['k_number']  = ['required', 'regex:/^[Kk]\d{7}$/', 'unique:users,student_id'];
        }

        if ($role === 'instructor') {
            $rules['department']  = 'required|in:business_it,liberal_arts,science,health_sciences,trades_technology,arts_humanities';
            $rules['badge_photo'] = 'required|image|mimes:jpg,jpeg,png|max:4096';
        }

        $validated = $request->validate($rules, [
            'email.unique'       => 'This email address is already registered. <a href="' . route('login') . '" class="underline">Sign in instead</a> or use a different email.',
            'consent.accepted'   => 'You must agree to the Terms & Conditions and Privacy Policy to register.',
            'k_number.required'  => 'Your K-number is required.',
            'k_number.regex'     => 'K-number must be in the format K followed by 7 digits (e.g. K1234567).',
            'k_number.unique'    => 'This K-number is already registered.',
        ]);

        // Enforce role-specific Kirkwood email domain
        $domain = substr(strrchr($validated['email'], '@'), 1);
        $requiredDomain = $role === 'student' ? 'student.kirkwood.edu' : 'kirkwood.edu';
        if ($domain !== $requiredDomain) {
            return back()->withErrors([
                'email' => $role === 'student'
                    ? 'Students must register with a <strong>@student.kirkwood.edu</strong> email address.'
                    : 'Instructors must register with a <strong>@kirkwood.edu</strong> email address.',
            ])->withInput();
        }

        // Handle badge photo upload for instructors
        $badgePhotoPath = null;
        if ($role === 'instructor' && $request->hasFile('badge_photo')) {
            $badgePhotoPath = $request->file('badge_photo')
                ->store('badge-photos', 'public');
        }

        // Generate verification code
        $verificationCode = strtoupper(Str::random(6));

        // Generate unique ID for instructors
        $instructorId = 'INS-' . date('Y') . '-' . strtoupper(Str::random(6));

        $user = User::create([
            'first_name'                   => $validated['first_name'],
            'last_name'                    => $validated['last_name'],
            'email'                        => $validated['email'],
            'password'                     => Hash::make($validated['password']),
            'phone_number'                 => $validated['phone_number'],
            'role'                         => $role,
            'major'                        => $role === 'student' ? $validated['major'] : null,
            'department'                   => $role === 'instructor' ? $validated['department'] : null,
            'badge_photo'                  => $badgePhotoPath,
            'student_id'                   => $role === 'student' ? strtoupper($validated['k_number']) : null,
            'instructor_id'                => $role === 'instructor' ? $instructorId : null,
            'verification_code'            => $verificationCode,
            'verification_code_expires_at' => now()->addHours(24),
            'approval_status'              => 'pending',
        ]);

//        // Send verification email via Mailpit (or any SMTP)
//        $verificationController = new EmailVerificationController();
//        $verificationController->sendVerificationEmail($user, $verificationCode);

        // Send verification email via Mailpit
        $verificationController = new EmailVerificationController();
        $verificationController->sendVerificationEmail($user, $verificationCode);

        session(['pending_verification_user_id' => $user->id]);

        return redirect()->route('verification.notice')
            ->with('info', 'Please check your email for your 6-digit verification code.');
    }
}
