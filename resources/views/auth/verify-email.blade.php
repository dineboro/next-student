@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg px-8 py-6">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Verify Your Email</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    We've sent a 6-digit verification code to<br>
                    <span class="font-semibold">{{ $user->email }}</span>
                </p>
            </div>

            <form method="POST" action="{{ route('verification.verify') }}">
                @csrf

                <!-- Verification Code -->
                <div class="mb-6">
                    <label for="verification_code" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Verification Code
                    </label>
                    <input
                        type="text"
                        name="verification_code"
                        id="verification_code"
                        maxlength="6"
                        required
                        autofocus
                        placeholder="Enter 6-digit code"
                        class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline text-center text-2xl tracking-widest @error('verification_code') border-red-500 @enderror">
                    @error('verification_code')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mb-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline transition">
                        Verify Email
                    </button>
                </div>

                <!-- Resend Code -->
                <div class="text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        Didn't receive the code?
                    </p>
                    <form method="POST" action="{{ route('verification.resend') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-semibold">
                            Resend Code
                        </button>
                    </form>
                </div>
            </form>

            <!-- Expiry Notice -->
            <div class="mt-6 p-3 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded">
                <p class="text-xs text-yellow-800 dark:text-yellow-200 text-center">
                    ⏰ This code will expire in 24 hours
                </p>
            </div>
        </div>
    </div>
@endsection
