@extends('layouts.app')

@section('title', 'Account Pending Approval')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full text-center">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                <div class="w-20 h-20 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center mx-auto mb-5">
                    <span class="text-4xl">⏳</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Pending Admin Approval</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">
                    Your email has been verified. An administrator will review your badge photo and approve your instructor account shortly.
                </p>

                <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-xl p-4 text-left mb-6">
                    <p class="text-xs font-semibold text-blue-800 dark:text-blue-300 uppercase tracking-wide mb-2">What happens next?</p>
                    <ul class="space-y-2 text-sm text-blue-700 dark:text-blue-200">
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5">📋</span>
                            Admin reviews your badge photo for identity verification
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5">📱</span>
                            You'll receive an SMS at <strong>{{ $user->phone_number }}</strong> once approved
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5">✅</span>
                            Then you can log in and start creating class sessions
                        </li>
                    </ul>
                </div>

                <a href="{{ route('login') }}"
                   class="inline-block px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg transition">
                    Back to Login
                </a>
            </div>
        </div>
    </div>
@endsection
