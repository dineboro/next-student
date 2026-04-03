<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ForgotEmailController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-email');
    }

    public function lookup(Request $request)
    {
        $request->validate([
            'kirkwood_id'  => 'required|string',
            'phone_number' => 'required|string',
        ]);

        $id    = strtoupper(trim($request->kirkwood_id));
        $phone = trim($request->phone_number);

        $user = User::where(function ($q) use ($id) {
                $q->where('student_id', $id)
                  ->orWhere('instructor_id', $id);
            })
            ->where('phone_number', $phone)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'kirkwood_id' => 'No account found matching that ID and phone number.',
            ])->withInput();
        }

        $masked = $this->maskEmail($user->email);

        return back()->with('found_email', $masked)->withInput();
    }

    private function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email);

        $visibleChars = min(2, strlen($local));
        $masked = substr($local, 0, $visibleChars) . str_repeat('*', max(0, strlen($local) - $visibleChars));

        return $masked . '@' . $domain;
    }
}
