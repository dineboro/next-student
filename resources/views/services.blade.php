@extends('layouts.app')

@section('title', 'Services — NextStudent')

@section('content')

    {{-- Page Header --}}
    <section class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white">Our Services</h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                NextStudent is a free academic support platform built for college students and faculty.
            </p>
        </div>
    </section>

    {{-- About the Platform --}}
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="prose dark:prose-invert max-w-none">

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">What Is NextStudent?</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                NextStudent is a web-based help-request management system designed to simplify communication between
                college students and their instructors. Instead of waiting in line or sending emails
                that get lost, students submit structured help requests online and get notified the moment an instructor
                responds via SMS text message.
            </p>

            <hr class="border-gray-200 dark:border-gray-700 my-10">

            {{-- Services list --}}
            <div class="space-y-10">

                {{-- Service 1 --}}
                <div class="flex gap-5">
                    <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">Help Request Submission</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Students submit detailed help requests from any device describing the topic, subject, urgency,
                            and attaching any relevant files or screenshots. Requests are organized by class section so
                            instructors see only their relevant students.
                        </p>
                    </div>
                </div>

                {{-- Service 2 --}}
                <div class="flex gap-5">
                    <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/40 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">Instructor Response & Comments</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Instructors review open requests from their dashboard and respond with comments directly in the
                            platform. Students and instructors can exchange follow-up messages in a threaded comment view
                            until the issue is resolved.
                        </p>
                    </div>
                </div>

                {{-- Service 3 --}}
                <div class="flex gap-5">
                    <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">SMS Text Notifications</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            When a request status changes for example, when an instructor responds or when a request is
                            marked complete users receive an SMS text message alert on the phone number they registered with.
                            Consent to receive SMS messages is collected separately during registration. Standard message and
                            data rates may apply. You can reply <strong>STOP</strong> at any time to opt out.
                        </p>
                    </div>
                </div>

                {{-- Service 4 --}}
                <div class="flex gap-5">
                    <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-yellow-100 dark:bg-yellow-900/40 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">Class Section Management</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Instructors create and manage class sections, enroll students, and keep requests organized by course.
                            Admins have full visibility across all users and can approve instructor accounts before they go live.
                        </p>
                    </div>
                </div>

                {{-- Service 5 --}}
                <div class="flex gap-5">
                    <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-red-100 dark:bg-red-900/40 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">Secure, Role-Based Access</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            All accounts are verified using .edu email addresses. Instructor accounts go through an
                            additional admin-approval step for security. Students, instructors, and administrators each
                            have dedicated dashboards with access only to what they need.
                        </p>
                    </div>
                </div>

            </div>

            <hr class="border-gray-200 dark:border-gray-700 my-10">

            {{-- Who can use it --}}
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Who Can Use NextStudent?</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-5">
                    <p class="font-semibold text-blue-900 dark:text-blue-200 mb-1">Students</p>
                    <p class="text-sm text-blue-800 dark:text-blue-300">
                        Enrolled college students with an active
                        <strong>@student.school.edu</strong> email address.
                    </p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-5">
                    <p class="font-semibold text-green-900 dark:text-green-200 mb-1">Faculty / Instructors</p>
                    <p class="text-sm text-green-800 dark:text-green-300">
                        College instructors with an active
                        <strong>@school.edu</strong> faculty email address.
                    </p>
                </div>
            </div>

        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
            <p class="text-gray-700 dark:text-gray-300 font-medium mb-6">
                Have questions or need help? Reach out to our support team.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('contact') }}"
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition text-sm">
                    Contact Support
                </a>
                <a href="{{ route('register') }}"
                   class="inline-block bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 font-semibold px-8 py-3 rounded-lg transition text-sm">
                    Create an Account
                </a>
            </div>
        </div>
    </section>

@endsection
