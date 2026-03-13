<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user     = Auth::user();
        $settings = $user->settings ?? $user->settings()->create([]);

        return view('settings.index', compact('user', 'settings'));
    }

    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications'   => 'boolean',
        ]);

        // Checkboxes that are unchecked are not submitted — default to false
        $validated['email_notifications'] = $request->boolean('email_notifications');
        $validated['sms_notifications']   = $request->boolean('sms_notifications');

        $user     = Auth::user();
        $settings = $user->settings ?? $user->settings()->create([]);

        $settings->update($validated);

        return back()->with('success', 'Notification preferences updated!');
    }

    public function updateTheme(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark,auto',
        ]);

        $user     = Auth::user();
        $settings = $user->settings ?? $user->settings()->create([]);

        $settings->update($validated);

        return back()->with('success', 'Theme preference updated!');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password'     => 'required',
            'confirmation' => 'required|in:DELETE',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'The provided password is incorrect.'
            ]);
        }

        // Block deletion if student has a pending request
        $activeRequests = $user->helpRequestsAsStudent()
            ->where('status', 'pending')
            ->count();

        if ($activeRequests > 0) {
            return back()->withErrors([
                'error' => 'You cannot delete your account while you have an active help request. Please cancel it first.'
            ]);
        }

        Auth::logout();
        $user->delete();

        return redirect()->route('login')
            ->with('success', 'Your account has been deleted successfully.');
    }
}
