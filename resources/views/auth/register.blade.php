@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="bg-white shadow-md rounded-lg px-8 py-6">
            <h2 class="text-2xl font-bold text-center mb-6">Create Account</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- School Selection -->
                <div class="mb-4">
                    <label for="school_id" class="block text-gray-700 text-sm font-bold mb-2">School *</label>
                    <select name="school_id" id="school_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('school_id') border-red-500 @enderror">
                        <option value="">Select School</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->school_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('school_id')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Selection -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">I am a *</label>
                    <div class="flex gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="role" value="student" {{ old('role') === 'student' ? 'checked' : '' }} required class="mr-2">
                            <span>Student</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="role" value="instructor" {{ old('role') === 'instructor' ? 'checked' : '' }} required class="mr-2">
                            <span>Instructor</span>
                        </label>
                    </div>
                    @error('role')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- First Name -->
                <div class="mb-4">
                    <label for="first_name" class="block text-gray-700 text-sm font-bold mb-2">First Name *</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('first_name') border-red-500 @enderror">
                    @error('first_name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div class="mb-4">
                    <label for="last_name" class="block text-gray-700 text-sm font-bold mb-2">Last Name *</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('last_name') border-red-500 @enderror">
                    @error('last_name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                    @error('email')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div class="mb-4">
                    <label for="phone_number" class="block text-gray-700 text-sm font-bold mb-2">Phone Number *</label>
                    <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" required placeholder="+1-555-555-0123" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('phone_number') border-red-500 @enderror">
                    @error('phone_number')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password *</label>
                    <input type="password" name="password" id="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                    @error('password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Register
                    </button>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Already have an account? Login
                    </a>
                </div>
                <div class="text-center mt-2">
                    <a href="{{ route('school-registration.create') }}" class="text-sm text-gray-600 hover:text-gray-800">
                        🏫 School not listed? Register it here
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection
