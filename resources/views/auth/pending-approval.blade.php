@extends('layouts.app')

@section('title', 'Pending Approval')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg px-8 py-6">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Account Pending Approval</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Your email has been verified successfully!
                </p>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">What's Next?</h3>
                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Your account is being reviewed by an administrator</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>You'll receive an email once your account is approved</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Approval typically takes 1-2 business days</span>
                    </li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Your Information</h4>
                <div class="space-y-1 text-sm text-gray-600 dark:text-gray-400">
                    <p><span class="font-medium">Name:</span> {{ $user->first_name }} {{ $user->last_name }}</p>
                    <p><span class="font-medium">Email:</span> {{ $user->email }}</p>
                    <p><span class="font-medium">Role:</span> {{ ucfirst($user->role) }}</p>
                    <p><span class="font-medium">School:</span> {{ $user->school->school_name }}</p>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-semibold">
                    Back to Login
                </a>
            </div>
        </div>
    </div>
@endsection
