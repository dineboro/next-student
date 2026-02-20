@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Profile</h1>
            <a href="{{ route('profile.edit') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Edit Profile
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <!-- Profile Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 h-32"></div>
            <div class="px-6 pb-6">
                <div class="flex items-end -mt-16 mb-6">
                    <div class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 bg-blue-600 flex items-center justify-center text-white text-4xl font-bold">
                        {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                    </div>
                    <div class="ml-6 mb-4">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ ucfirst($user->role) }} • {{ $user->school->school_name }}
                        </p>
                    </div>
                </div>

                <!-- Profile Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Contact Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="text-sm text-gray-900 dark:text-white mt-1">{{ $user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                <dd class="text-sm text-gray-900 dark:text-white mt-1">{{ $user->phone_number }}</dd>
                            </div>
                            @if($user->role === 'student')
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Student ID</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white mt-1">{{ $user->student_id }}</dd>
                                </div>
                            @else
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Instructor ID</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white mt-1">{{ $user->instructor_id }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Account Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Account Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">School</dt>
                                <dd class="text-sm text-gray-900 dark:text-white mt-1">{{ $user->school->school_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Member Since</dt>
                                <dd class="text-sm text-gray-900 dark:text-white mt-1">{{ $user->created_at->format('F Y') }}</dd>
                            </div>
                            @if($user->role === 'instructor')
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Availability</dt>
                                    <dd class="text-sm mt-1">
                                <span class="px-2 py-1 text-xs rounded {{ $user->is_available ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ $user->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Bio Section -->
                @if($user->bio)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">About</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->bio }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
