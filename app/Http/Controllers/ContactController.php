<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:200'],
            'subject' => ['required', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        // Log the contact request; email delivery can be wired up when mail is configured.
        \Log::info('Contact form submission', [
            'name'    => $request->input('name'),
            'email'   => $request->input('email'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
        ]);

        return redirect()->route('contact')->with('contact_success', true);
    }
}
