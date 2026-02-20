@extends('layouts.app')

@section('title', 'Register School')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg px-8 py-6">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Register Your School</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Submit your school information for admin approval
                </p>
            </div>

            <form method="POST" action="{{ route('school-registration.store') }}">
                @csrf

                <!-- School Information Section -->
                <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">School Information</h3>

                    <!-- School Name -->
                    <div class="mb-4">
                        <label for="school_name" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            School Name *
                        </label>
                        <input
                            type="text"
                            name="school_name"
                            id="school_name"
                            value="{{ old('school_name') }}"
                            required
                            placeholder="e.g., Central Technical Institute"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline @error('school_name') border-red-500 @enderror">
                        @error('school_name')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- School Domain -->
                    <div class="mb-4">
                        <label for="school_domain" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            School Email Domain *
                        </label>
                        <input
                            type="text"
                            name="school_domain"
                            id="school_domain"
                            value="{{ old('school_domain') }}"
                            required
                            placeholder="e.g., centraltech.edu"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline @error('school_domain') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Enter domain without @ (e.g., school.edu)
                        </p>
                        @error('school_domain')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="mb-4">
                        <label for="address" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            School Address *
                        </label>
                        <textarea
                            name="address"
                            id="address"
                            rows="3"
                            required
                            placeholder="Complete mailing address"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                        @error('address')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Info -->
                    <div class="mb-4">
                        <label for="contact_info" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            School Contact Number *
                        </label>
                        <input
                            type="text"
                            name="contact_info"
                            id="contact_info"
                            value="{{ old('contact_info') }}"
                            required
                            placeholder="e.g., +1-555-123-4567"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline @error('contact_info') border-red-500 @enderror">
                        @error('contact_info')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Registration ID -->
                    <div class="mb-4">
                        <label for="registration_id" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            State/Federal Registration ID *
                        </label>
                        <input
                            type="text"
                            name="registration_id"
                            id="registration_id"
                            value="{{ old('registration_id') }}"
                            required
                            placeholder="Official government registration number"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline @error('registration_id') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            This ID will be verified with government records
                        </p>
                        @error('registration_id')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Requester Information Section -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Your Information</h3>

                    <!-- Requester Name -->
                    <div class="mb-4">
                        <label for="requester_name" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            Your Full Name *
                        </label>
                        <input
                            type="text"
                            name="requester_name"
                            id="requester_name"
                            value="{{ old('requester_name') }}"
                            required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline @error('requester_name') border-red-500 @enderror">
                        @error('requester_name')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Requester Email -->
                    <div class="mb-4">
                        <label for="requester_email" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            Your Email *
                        </label>
                        <input
                            type="email"
                            name="requester_email"
                            id="requester_email"
                            value="{{ old('requester_email') }}"
                            required
                            placeholder="your.email@schooldomain.edu"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline @error('requester_email') border-red-500 @enderror">
                        @error('requester_email')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Requester Phone -->
                    <div class="mb-4">
                        <label for="requester_phone" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            Your Phone Number (Optional)
                        </label>
                        <input
                            type="tel"
                            name="requester_phone"
                            id="requester_phone"
                            value="{{ old('requester_phone') }}"
                            placeholder="+1-555-987-6543"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline @error('requester_phone') border-red-500 @enderror">
                        @error('requester_phone')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded p-4 mb-6">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>Note:</strong> Your school registration request will be reviewed by our administrators.
                        You will receive an email notification once your request is approved or if additional information is needed.
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('login') }}"
                       class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-semibold">
                        Back to Login
                    </a>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
