@extends('layouts.app')

@section('title', 'Contact & Support — NextStudent')

@section('content')

    {{-- Page Header --}}
    <section class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white">Contact &amp; Support</h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-300 max-w-xl mx-auto">
                We're here to help. Reach out with questions, feedback, or technical issues.
            </p>
        </div>
    </section>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

            {{-- Contact Info --}}
            <div class="space-y-8">

                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Business Information</h2>
                    <dl class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex gap-3">
                            <dt class="font-semibold text-gray-800 dark:text-gray-200 w-28 flex-shrink-0">Organization</dt>
                            <dd>NextStudent Academic Help Platform</dd>
                        </div>
                        <div class="flex gap-3">
                            <dt class="font-semibold text-gray-800 dark:text-gray-200 w-28 flex-shrink-0">Address</dt>
                            <dd>
                                Kirkwood Community College<br>
                                6301 Kirkwood Blvd SW<br>
                                Cedar Rapids, IA 52404
                            </dd>
                        </div>
                        <div class="flex gap-3">
                            <dt class="font-semibold text-gray-800 dark:text-gray-200 w-28 flex-shrink-0">Email</dt>
                            <dd>
                                <a href="mailto:support@nextstudent.org"
                                   class="text-blue-600 dark:text-blue-400 hover:underline">
                                    support@nextstudent.org
                                </a>
                            </dd>
                        </div>
                        <div class="flex gap-3">
                            <dt class="font-semibold text-gray-800 dark:text-gray-200 w-28 flex-shrink-0">Website</dt>
                            <dd>nextstudent.org</dd>
                        </div>
                    </dl>
                </div>

                <hr class="border-gray-200 dark:border-gray-700">

                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">SMS &amp; Messaging Support</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                        NextStudent sends SMS text notifications to registered users when their help requests are updated.
                        Consent to receive these messages is collected during account registration.
                    </p>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1 list-disc list-inside">
                        <li>Reply <strong>STOP</strong> to any message to opt out of SMS notifications.</li>
                        <li>Reply <strong>HELP</strong> to any message for assistance.</li>
                        <li>Message &amp; data rates may apply.</li>
                        <li>To re-enable notifications, update your settings in the app.</li>
                    </ul>
                </div>

                <hr class="border-gray-200 dark:border-gray-700">

                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Legal</h2>
                    <div class="flex gap-4 text-sm">
                        <a href="{{ route('legal.privacy') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                            Privacy Policy
                        </a>
                        <span class="text-gray-400">&middot;</span>
                        <a href="{{ route('legal.terms') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                            Terms &amp; Conditions
                        </a>
                    </div>
                </div>

            </div>

            {{-- Support Form --}}
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Send a Message</h2>

                @if(session('contact_success'))
                    <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg mb-6 text-sm">
                        Thank you! Your message has been received. We'll get back to you at the email address you provided.
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.send') }}"
                      class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('name') border-red-500 @enderror">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('email') border-red-500 @enderror">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject *</label>
                        <select name="subject" required
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('subject') border-red-500 @enderror">
                            <option value="">Select a topic</option>
                            <option value="technical_issue" {{ old('subject') === 'technical_issue' ? 'selected' : '' }}>Technical Issue</option>
                            <option value="account_help"    {{ old('subject') === 'account_help'    ? 'selected' : '' }}>Account Help</option>
                            <option value="sms_opt_out"     {{ old('subject') === 'sms_opt_out'     ? 'selected' : '' }}>SMS / Text Message Opt-Out</option>
                            <option value="general"         {{ old('subject') === 'general'         ? 'selected' : '' }}>General Question</option>
                            <option value="other"           {{ old('subject') === 'other'           ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('subject')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message *</label>
                        <textarea name="message" rows="5" required
                                  class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                        @error('message')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition text-sm">
                        Send Message
                    </button>
                </form>
            </div>

        </div>
    </div>

@endsection
