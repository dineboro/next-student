@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Hello, {{ auth()->user()->first_name }}! 👋
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->major }}</p>
        </div>

        @foreach(['success','warning','info','error'] as $type)
            @if(session($type))
                <div class="rounded-lg p-3 mb-4 text-sm
                    {{ $type === 'success' ? 'bg-green-50 dark:bg-green-900/30 border border-green-200 text-green-800 dark:text-green-200' : '' }}
                    {{ $type === 'warning' ? 'bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 text-yellow-800 dark:text-yellow-200' : '' }}
                    {{ $type === 'error'   ? 'bg-red-50 dark:bg-red-900/30 border border-red-200 text-red-800 dark:text-red-200' : '' }}
                    {{ $type === 'info'    ? 'bg-blue-50 dark:bg-blue-900/30 border border-blue-200 text-blue-800 dark:text-blue-200' : '' }}">
                    {{ session($type) }}
                </div>
            @endif
        @endforeach

        {{-- Active Request Banner --}}
        @if($activeRequest)
            <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 rounded-xl p-5 mb-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-yellow-700 dark:text-yellow-400 uppercase tracking-wide mb-1">🕐 Active Request</p>
                        <p class="font-semibold text-gray-900 dark:text-white text-lg">{{ $activeRequest->title }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            {{ $activeRequest->classSection?->course_name }}
                            @if($activeRequest->assignedInstructor)
                                · Instructor: {{ $activeRequest->assignedInstructor->fullName() }}
                            @endif
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">📍 {{ $activeRequest->location }}</p>
                    </div>
                    <div class="flex flex-col gap-2 ml-4">
                        <a href="{{ route('student.requests.show', $activeRequest) }}"
                           class="px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-medium rounded-lg transition text-center">
                            View & Chat
                        </a>
                        <a href="{{ route('student.requests.cancel-form', $activeRequest) }}"
                           class="px-3 py-1.5 border border-red-300 text-red-600 dark:border-red-600 dark:text-red-400 text-xs font-medium rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30 transition text-center">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        @else
            {{-- New Request CTA --}}
            @if($enrolledSections->isNotEmpty())
                <div class="bg-blue-600 rounded-xl p-6 mb-6 text-white flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-lg">Need help?</p>
                        <p class="text-blue-100 text-sm mt-0.5">Submit a request and your instructor will come to you.</p>
                    </div>
                    <a href="{{ route('student.requests.create') }}"
                       class="px-5 py-2.5 bg-white text-blue-700 font-semibold text-sm rounded-lg hover:bg-blue-50 transition">
                        Request Help
                    </a>
                </div>
            @else
                <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 mb-6 text-center">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        You are not enrolled in any active class sections yet. Ask your instructor to add you.
                    </p>
                </div>
            @endif
        @endif

        {{-- Enrolled Sections --}}
        @if($enrolledSections->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 mb-6">
                <h2 class="font-semibold text-gray-900 dark:text-white mb-3">My Classes</h2>
                <div class="space-y-2">
                    @foreach($enrolledSections as $section)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $section->course_name }}</p>
                                <p class="text-xs text-gray-400">
                                    {{ $section->course_code }} · {{ $section->semesterLabel() }} · {{ $section->instructor->fullName() }}
                                </p>
                            </div>
                            <span class="text-xs px-2 py-0.5 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-full">Active</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Past Requests --}}
        @if($pastRequests->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-semibold text-gray-900 dark:text-white">Past Requests</h2>
                    <a href="{{ route('student.my-requests') }}" class="text-xs text-blue-600 hover:underline">View all</a>
                </div>
                <div class="space-y-2">
                    @foreach($pastRequests as $req)
                        <a href="{{ route('student.requests.show', $req) }}"
                           class="flex items-center justify-between py-2.5 px-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition border border-transparent hover:border-gray-200 dark:hover:border-gray-600">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $req->title }}</p>
                                <p class="text-xs text-gray-400">
                                    {{ $req->classSection?->course_code }} · {{ $req->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $req->statusBadgeClass() }}">
                                {{ $req->statusLabel() }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
