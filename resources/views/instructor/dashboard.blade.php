@extends('layouts.app')

@section('title', 'Instructor Dashboard')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Welcome, {{ auth()->user()->first_name }}! 👋
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ auth()->user()->departmentLabel() }} · {{ auth()->user()->instructor_id }}
                </p>
            </div>
            <a href="{{ route('instructor.sections.index') }}"
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">
                📚 My sections
            </a>
        </div>

        @if(section('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 rounded-lg p-3 mb-4 text-sm text-green-800 dark:text-green-200">{{ section('success') }}</div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pending_requests'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pending Requests</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['completed_today'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Completed Today</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total_completed'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total Completed</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['active_sections'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Active sections</p>
            </div>
        </div>

        {{-- Pending Requests --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="font-semibold text-gray-900 dark:text-white">Pending Help Requests</h2>
            </div>

            @if($pendingRequests->isEmpty())
                <div class="p-12 text-center">
                    <div class="text-5xl mb-3">🎉</div>
                    <p class="text-gray-500 dark:text-gray-400">No pending requests. You're all caught up!</p>
                </div>
            @else
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($pendingRequests as $request)
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $request->title }}</p>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1 line-clamp-2">{{ $request->description }}</p>
                                    <div class="flex flex-wrap gap-3 text-xs text-gray-400">
                                        <span>👤 {{ $request->student->fullName() }}</span>
                                        <span>📍 {{ $request->location }}</span>
                                        <span>📚 {{ $request->classsection?->course_code }}</span>
                                        <span>🕐 {{ $request->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if($request->image)
                                        <p class="text-xs text-blue-500 mt-1">📎 Has photo attached</p>
                                    @endif
                                </div>
                                <div class="flex flex-col gap-2 flex-shrink-0">
                                    <a href="{{ route('instructor.requests.show', $request) }}"
                                       class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition text-center">
                                        View & Chat
                                    </a>
                                    <form method="POST" action="{{ route('instructor.requests.complete', $request) }}">
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('Mark this request as complete?')"
                                                class="w-full px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition">
                                            ✓ Complete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
