
@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="bg-white shadow-md rounded-lg px-8 py-6">
            <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                    @error('email')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" id="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                    @error('password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="mr-2">
                        <span class="text-sm text-gray-700">Remember me</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Login
                    </button>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Don't have an account? Register
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
