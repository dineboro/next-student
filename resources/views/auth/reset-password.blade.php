@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="bg-white shadow-md rounded-lg px-8 py-6">
            <h2 class="text-2xl font-bold text-center mb-2">Reset Password</h2>
            <p class="text-sm text-gray-500 text-center mb-6">Enter the 6-character code from your email and choose a new password.</p>

            @if (session('info'))
                <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-700 text-sm rounded px-4 py-3">
                    {{ session('info') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.reset') }}">
                @csrf

                <div class="mb-4">
                    <label for="code" class="block text-gray-700 text-sm font-bold mb-2">Reset Code</label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" required autofocus
                           maxlength="6" placeholder="XXXXXX"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 uppercase tracking-widest text-center text-lg leading-tight focus:outline-none focus:shadow-outline @error('code') border-red-500 @enderror">
                    @error('code')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">New Password</label>
                    <input type="password" name="password" id="password" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Reset Password
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('password.forgot') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    Didn't receive a code? Request one
                </a>
            </div>
        </div>
    </div>
@endsection
