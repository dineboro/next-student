@extends('layouts.app')

@section('title', 'Cancel Request')

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Cancel Help Request</h2>
            </div>

            <div class="p-6">
                <!-- Request Summary -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Request Details</h3>
                    <dl class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Category:</dt>
                            <dd class="text-gray-900 dark:text-white font-medium">{{ $helpRequest->category->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Vehicle:</dt>
                            <dd class="text-gray-900 dark:text-white font-medium">{{ $helpRequest->vehicle->make }} {{ $helpRequest->vehicle->model }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Priority:</dt>
                            <dd><span class="px-2 py-1 text-xs rounded {{ $helpRequest->priority_level === 'emergency' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : ($helpRequest->priority_level === 'high' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : ($helpRequest->priority_level === 'medium' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200')) }}">
                            {{ ucfirst($helpRequest->priority_level) }}
                        </span></dd>
                        </div>
                    </dl>
                </div>

                <!-- Warning -->
                <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400 dark:text-red-300 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Warning</h3>
                            <p class="mt-1 text-sm text-red-700 dark:text-red-300">
                                This action cannot be undone. Once cancelled, you'll need to create a new request if you still need help.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Cancellation Form -->
                <form method="POST" action="{{ route('student.requests.cancel', $helpRequest) }}">
                    @csrf

                    <div class="mb-6">
                        <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Reason for Cancellation *
                        </label>
                        <textarea name="cancellation_reason" id="cancellation_reason" rows="4" required
                                  placeholder="Please explain why you're cancelling this request..."
                                  class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 dark:bg-gray-700 dark:text-white @error('cancellation_reason') border-red-500 @enderror">{{ old('cancellation_reason') }}</textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">This helps us improve our service</p>
                        @error('cancellation_reason')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('student.dashboard') }}"
                           class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Go Back
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-medium"
                                onclick="return confirm('Are you sure you want to cancel this request?')">
                            Cancel Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
