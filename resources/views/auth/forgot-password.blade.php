@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="bg-white shadow-md rounded-lg px-8 py-6">
            <h2 class="text-2xl font-bold text-center mb-2">Forgot Password</h2>
            <p class="text-sm text-gray-500 text-center mb-6">Enter your email and we'll send you a reset code.</p>

            @if (session('info'))
                <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-700 text-sm rounded px-4 py-3">
                    {{ session('info') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.forgot.send') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Send Reset Code
                </button>
            </form>

            <div class="text-center mt-4 space-y-1">
                <a href="{{ route('password.reset.form') }}" class="block text-sm text-blue-600 hover:text-blue-800">
                    Already have a code? Enter it here
                </a>
                <a href="{{ route('login') }}" class="block text-sm text-gray-500 hover:text-gray-700">
                    Back to login
                </a>
            </div>
        </div>
    </div>
@endsection
