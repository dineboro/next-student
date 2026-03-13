<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'phone_number'  => 'required|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('profile-photos', 'public');
        }

        $user->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The provided password does not match your current password.'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        // Enforce Kirkwood domain
        $domain = substr(strrchr($request->email, '@'), 1);
        if ($domain !== 'kirkwood.edu') {
            return back()->withErrors([
                'email' => 'Email must use your Kirkwood email (@kirkwood.edu).'
            ]);
        }

        $user = Auth::user();

        $user->update([
            'email'                         => $request->email,
            'email_verified_at'             => null,
            'verification_code'             => strtoupper(Str::random(6)),
            'verification_code_expires_at'  => now()->addHours(24),
        ]);

        // TODO: Send verification email

        Auth::logout();

        return redirect()->route('verification.notice')
            ->with('info', 'Email updated! Please verify your new email address.');
    }
}
