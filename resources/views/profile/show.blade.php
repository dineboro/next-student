@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-8">

        @if(session('success'))
            <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Profile Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            {{-- Header Banner --}}
            <div class="h-24 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

            <div class="px-6 pb-6">
                {{-- Avatar --}}
                <div class="flex items-end justify-between -mt-12 mb-4">
                    <div>
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                 alt="{{ $user->fullName() }}"
                                 class="w-24 h-24 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow">
                        @else
                            <div class="w-24 h-24 rounded-full bg-blue-600 flex items-center justify-center text-white text-3xl font-bold border-4 border-white dark:border-gray-800 shadow">
                                {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <a href="{{ route('profile.edit') }}"
                       class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                        Edit Profile
                    </a>
                </div>

                {{-- Name & Role --}}
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->fullName() }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    {{ ucfirst($user->role) }}
                    @if($user->role === 'instructor' && $user->department)
                        · {{ $user->departmentLabel() }}
                    @elseif($user->role === 'student' && $user->major)
                        · {{ $user->major }}
                    @endif
                </p>

                {{-- Info Grid --}}
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Email</p>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $user->email }}</p>
                    </div>

                    @if($user->phone_number)
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Phone</p>
                            <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $user->phone_number }}</p>
                        </div>
                    @endif

                    @if($user->role === 'student' && $user->student_id)
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Student ID</p>
                            <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $user->student_id }}</p>
                        </div>
                    @endif

                    @if($user->role === 'instructor' && $user->instructor_id)
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Instructor ID</p>
                            <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $user->instructor_id }}</p>
                        </div>
                    @endif

                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Member Since</p>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $user->created_at->format('F j, Y') }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Email Verified</p>
                        <p class="text-sm mt-1">
                            @if($user->isEmailVerified())
                                <span class="text-green-600 dark:text-green-400">Verified</span>
                            @else
                                <span class="text-red-500">Not verified</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('settings.index') }}"
                       class="text-sm px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Settings
                    </a>
                    <a href="{{ route('notifications.index') }}"
                       class="text-sm px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Notifications
                    </a>
                    @if($user->role === 'student')
                        <a href="{{ route('student.my-requests') }}"
                           class="text-sm px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            My Requests
                        </a>
                    @elseif($user->role === 'instructor')
                        <a href="{{ route('instructor.history') }}"
                           class="text-sm px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Request History
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
