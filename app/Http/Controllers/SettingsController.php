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
        $user = Auth::user();
        $settings = $user->settings ?? $user->settings()->create([]);

        return view('settings.index', compact('user', 'settings'));
    }

    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'notify_email' => 'boolean',
            'notify_sms' => 'boolean',
            'notify_push' => 'boolean',
            'notify_request_assigned' => 'boolean',
            'notify_request_updated' => 'boolean',
            'notify_request_completed' => 'boolean',
            'notify_new_comment' => 'boolean',
        ]);

        $user = Auth::user();
        $settings = $user->settings ?? $user->settings()->create([]);

        $settings->update($validated);

        return back()->with('success', 'Notification preferences updated successfully!');
    }

    public function updatePrivacy(Request $request)
    {
        $validated = $request->validate([
            'profile_visible' => 'boolean',
            'show_email' => 'boolean',
            'show_phone' => 'boolean',
            'allow_messages' => 'boolean',
        ]);

        $user = Auth::user();
        $settings = $user->settings ?? $user->settings()->create([]);

        $settings->update($validated);

        return back()->with('success', 'Privacy settings updated successfully!');
    }

    public function updateTheme(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark,auto',
            'language' => 'required|string|max:10',
        ]);

        $user = Auth::user();
        $settings = $user->settings ?? $user->settings()->create([]);

        $settings->update($validated);

        return back()->with('success', 'Theme settings updated successfully!');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'confirmation' => 'required|in:DELETE',
        ]);

        $user = Auth::user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'The provided password is incorrect.'
            ]);
        }

        // Check for active requests
        $activeRequests = $user->helpRequestsAsStudent()
            ->whereIn('status', ['pending', 'assigned', 'in_progress'])
            ->count();

        if ($activeRequests > 0) {
            return back()->withErrors([
                'error' => 'You cannot delete your account while you have active requests. Please cancel or complete them first.'
            ]);
        }

        // Soft delete user
        Auth::logout();
        $user->delete();

        return redirect()->route('login')
            ->with('success', 'Your account has been deleted successfully.');
    }
}
