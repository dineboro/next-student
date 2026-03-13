@extends('layouts.app')

@section('title', 'Cancel Help Request')

@section('content')
    <div class="max-w-lg mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('student.requests.show', $helpRequest) }}"
               class="text-sm text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                ← Back to Request
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Cancel Help Request</h1>
        </div>

        {{-- Request Summary --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 mb-5">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-3">Request to be cancelled</p>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Title</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $helpRequest->title }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Class</span>
                    <span class="font-medium text-gray-900 dark:text-white">
                    {{ $helpRequest->classSection?->course_name }}
                    ({{ $helpRequest->classSection?->course_code }})
                </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Instructor</span>
                    <span class="font-medium text-gray-900 dark:text-white">
                    {{ $helpRequest->assignedInstructor?->fullName() ?? '—' }}
                </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Location</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $helpRequest->location }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Submitted</span>
                    <span class="font-medium text-gray-900 dark:text-white">
                    {{ $helpRequest->created_at->diffForHumans() }}
                </span>
                </div>
            </div>
        </div>

        {{-- Warning --}}
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-xl p-4 mb-5">
            <div class="flex items-start gap-3">
                <span class="text-xl">⚠️</span>
                <div>
                    <p class="text-sm font-semibold text-red-800 dark:text-red-300">Are you sure?</p>
                    <p class="text-sm text-red-700 dark:text-red-400 mt-1">
                        Cancelling this request will notify your instructor by SMS.
                        You can submit a new request at any time.
                    </p>
                </div>
            </div>
        </div>

        {{-- Cancel Form --}}
        <form method="POST" action="{{ route('student.requests.cancel', $helpRequest) }}"
              class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Reason for cancellation
                    <span class="font-normal text-gray-400">(optional)</span>
                </label>
                <textarea name="cancellation_reason" rows="3"
                          placeholder="e.g. I figured it out, no longer need help..."
                          class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:outline-none">{{ old('cancellation_reason') }}</textarea>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('student.requests.show', $helpRequest) }}"
                   class="flex-1 text-center border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium py-2.5 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Keep Request
                </a>
                <button type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 rounded-lg text-sm transition">
                    Yes, Cancel Request
                </button>
            </div>
        </form>
    </div>
@endsection
