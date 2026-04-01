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
        ];

        if ($role === 'student') {
            $rules['major'] = 'required|string|max:255';
        }

        if ($role === 'instructor') {
            $rules['department']  = 'required|in:business_it,liberal_arts,science,health_sciences,trades_technology,arts_humanities';
            $rules['badge_photo'] = 'required|image|mimes:jpg,jpeg,png|max:4096';
        }

        $validated = $request->validate($rules);

        // Enforce Kirkwood email domain
        $domain = substr(strrchr($validated['email'], '@'), 1);
        if (!in_array($domain, self::ALLOWED_DOMAIN)) {
            return back()->withErrors([
                'email' => 'Only the following email addresses are allowed to register:<br>' . implode('<br>', self::ALLOWED_DOMAIN),
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

        // Generate unique ID
        $idPrefix = $role === 'student' ? 'STU-' . date('Y') . '-' : 'INS-' . date('Y') . '-';
        $uniqueId = $idPrefix . strtoupper(Str::random(6));


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
            'student_id'                   => $role === 'student' ? $uniqueId : null,
            'instructor_id'                => $role === 'instructor' ? $uniqueId : null,
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
