@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">NextStudent Help System</p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            <a href="{{ route('admin.users.pending') }}"
               class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center hover:shadow-lg transition hover:ring-2 hover:ring-yellow-400">
                <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pending_instructors'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pending Instructors</p>
            </a>
            <a href="{{ route('admin.students') }}"
               class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center hover:shadow-lg transition hover:ring-2 hover:ring-blue-400">
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total_students'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Students</p>
            </a>
            <a href="{{ route('admin.instructors') }}"
               class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center hover:shadow-lg transition hover:ring-2 hover:ring-purple-400">
                <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['total_instructors'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Instructors</p>
            </a>
            <a href="{{ route('admin.requests', ['filter' => 'active']) }}"
               class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center hover:shadow-lg transition hover:ring-2 hover:ring-orange-400">
                <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $stats['active_requests'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Active Requests</p>
            </a>
            <a href="{{ route('admin.requests', ['filter' => 'completed']) }}"
               class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center hover:shadow-lg transition hover:ring-2 hover:ring-green-400">
                <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['completed_total'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Completed</p>
            </a>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('admin.users.pending') }}"
               class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 hover:shadow-lg transition flex items-center gap-4">
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center text-2xl">
                    👨‍🏫
                </div>
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">Instructor Approvals</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Review badge photos & approve instructors</p>
                    @if($stats['pending_instructors'] > 0)
                        <span class="inline-block mt-1 px-2 py-0.5 text-xs bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded-full font-medium">
                            {{ $stats['pending_instructors'] }} pending
                        </span>
                    @endif
                </div>
            </a>

            <a href="{{ route('admin.students') }}"
               class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 hover:shadow-lg transition flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center text-2xl">
                    🎓
                </div>
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">All Students</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $stats['total_students'] }} registered students</p>
                </div>
            </a>

            <a href="{{ route('admin.requests') }}"
               class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 hover:shadow-lg transition flex items-center gap-4">
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center text-2xl">
                    📋
                </div>
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">All Requests</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $stats['active_requests'] }} active · {{ $stats['completed_total'] }} completed</p>
                </div>
            </a>
        </div>

        {{-- Recent Pending Instructors --}}
        @if($pendingInstructors->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-900 dark:text-white">Recent Pending Instructors</h2>
                    <a href="{{ route('admin.users.pending') }}" class="text-sm text-blue-600 hover:underline">View all</a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($pendingInstructors as $instructor)
                        <div class="px-5 py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                @if($instructor->badge_photo)
                                    <img src="{{ asset('storage/' . $instructor->badge_photo) }}"
                                         alt="" class="w-10 h-10 rounded-lg object-cover border border-gray-200 dark:border-gray-700">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-lg">👤</div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $instructor->fullName() }}</p>
                                    <p class="text-xs text-gray-400">{{ $instructor->departmentLabel() }} · {{ $instructor->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.users.pending') }}"
                               class="text-xs px-3 py-1 border border-blue-300 text-blue-600 dark:border-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30 transition">
                                Review
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection