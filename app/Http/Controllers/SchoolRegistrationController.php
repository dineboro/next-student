<?php

namespace App\Http\Controllers;

use App\Models\SchoolRegistrationRequest;
use Illuminate\Http\Request;

class SchoolRegistrationController extends Controller
{
    public function showForm()
    {
        return view('school-registration.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'school_domain' => 'required|string|max:255|unique:school_registration_requests,school_domain|unique:schools,school_domain',
            'address' => 'required|string|max:500',
            'contact_info' => 'required|string|max:255',
            'registration_id' => 'required|string|max:255|unique:school_registration_requests,registration_id|unique:schools,registration_id',
            'requester_name' => 'required|string|max:255',
            'requester_email' => 'required|email|max:255',
            'requester_phone' => 'nullable|string|max:20',
        ]);

        $registrationRequest = SchoolRegistrationRequest::create($validated);

        // TODO: Send email notification to admins

        return redirect()->route('school-registration.success')
            ->with('success', 'School registration request submitted! You will be notified once reviewed.');
    }

    public function success()
    {
        return view('school-registration.success');
    }

}
