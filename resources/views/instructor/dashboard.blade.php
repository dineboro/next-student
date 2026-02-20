@extends('layouts.app')

@section('title', 'Instructor Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->first_name }}!</h1>
                <p class="text-gray-600">Instructor ID: {{ auth()->user()->instructor_id }}</p>
            </div>
            <form method="POST" action="{{ route('instructor.toggle-availability') }}">
                @csrf
                <button type="submit" class="px-6 py-3 rounded-lg font-medium {{ auth()->user()->is_available ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}">
                    {{ auth()->user()->is_available ? '🔴 Mark Unavailable' : '🟢 Mark Available' }}
                </button>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm">Active Requests</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['active_requests'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm">Completed Today</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['completed_today'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm">Average Rating</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['average_rating'] ?? 0, 1) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm">Total Completed</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_completed'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Assigned Requests -->
        @if($assignedRequests->count() > 0)
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">My Assigned Requests</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($assignedRequests as $request)
                        <div class="p-6 hover:bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $request->title ?? $request->category->name }}</h3>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($request->priority_level === 'emergency') bg-red-100 text-red-800
                                        @elseif($request->priority_level === 'high') bg-orange-100 text-orange-800
                                        @elseif($request->priority_level === 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst($request->priority_level) }}
                                    </span>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $request->status === 'in_progress' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                    </span>
                                    </div>

                                    <p class="text-gray-600 mb-3">{{ $request->description }}</p>

                                    <div class="grid grid-cols-4 gap-4 text-sm">
                                        <div>
                                            <p class="text-gray-500">Student</p>
                                            <p class="font-medium">{{ $request->student->first_name }} {{ $request->student->last_name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Vehicle</p>
                                            <p class="font-medium">{{ $request->vehicle->make }} {{ $request->vehicle->model }}</p>
                                        </div>
                                        @if($request->bay)
                                            <div>
                                                <p class="text-gray-500">Bay</p>
                                                <p class="font-medium">{{ $request->bay->bay_number }}</p>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-gray-500">Created</p>
                                            <p class="font-medium">{{ $request->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="ml-6 flex flex-col gap-2">
                                    <a href="{{ route('help-requests.show', $request) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-center">
                                        View Details
                                    </a>

                                    @if($request->status === 'assigned')
                                        <form method="POST" action="{{ route('help-requests.update', $request) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="in_progress">
                                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                                Start Work
                                            </button>
                                        </form>
                                    @endif

                                    @if($request->status === 'in_progress')
                                        <form method="POST" action="{{ route('help-requests.update', $request) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                                Mark Complete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Pending Requests Queue -->
        @if($pendingRequests->count() > 0 && auth()->user()->is_available)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Pending Requests Queue</h2>
                    <p class="text-sm text-gray-600">Students waiting for help</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Wait Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingRequests as $request)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $request->queuePosition->position ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $request->student->first_name }} {{ $request->student->last_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $request->category->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($request->priority_level === 'emergency') bg-red-100 text-red-800
                                    @elseif($request->priority_level === 'high') bg-orange-100 text-orange-800
                                    @elseif($request->priority_level === 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($request->priority_level) }}
                                </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $request->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $request->vehicle->make }} {{ $request->vehicle->model }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection
