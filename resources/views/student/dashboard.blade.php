
@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->first_name }}!</h1>
            <p class="text-gray-600">Student ID: {{ auth()->user()->student_id }}</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm">Available Instructors</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $availableInstructors }}</p>
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
                        <p class="text-gray-500 text-sm">Available Bays</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $availableBays }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm">Available Vehicles</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $availableVehicles }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Request -->
        @if($activeRequest)
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Active Request</h2>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ $activeRequest->title ?? $activeRequest->category->name }}</h3>
                            <p class="text-gray-600 mt-2">{{ $activeRequest->description }}</p>

                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Vehicle</p>
                                    <p class="font-medium">{{ $activeRequest->vehicle->make }} {{ $activeRequest->vehicle->model }}</p>
                                </div>
                                @if($activeRequest->bay)
                                    <div>
                                        <p class="text-sm text-gray-500">Bay</p>
                                        <p class="font-medium">{{ $activeRequest->bay->bay_number }}</p>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm text-gray-500">Priority</p>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($activeRequest->priority_level === 'emergency') bg-red-100 text-red-800
                                    @elseif($activeRequest->priority_level === 'high') bg-orange-100 text-orange-800
                                    @elseif($activeRequest->priority_level === 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($activeRequest->priority_level) }}
                                </span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($activeRequest->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($activeRequest->status === 'assigned') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($activeRequest->status) }}
                                </span>
                                </div>
                            </div>

                            @if($activeRequest->assignedInstructor)
                                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                    <p class="text-sm text-blue-800 font-medium">Assigned Instructor</p>
                                    <p class="text-blue-900">{{ $activeRequest->assignedInstructor->first_name }} {{ $activeRequest->assignedInstructor->last_name }}</p>
                                    <p class="text-sm text-blue-700">{{ $activeRequest->assignedInstructor->phone_number }}</p>
                                </div>
                            @elseif($queuePosition)
                                <div class="mt-4 p-4 bg-yellow-50 rounded-lg">
                                    <p class="text-sm text-yellow-800 font-medium">Queue Position</p>
                                    <p class="text-2xl font-bold text-yellow-900">{{ $queuePosition->position }}</p>
                                    @if($estimatedWaitTime)
                                        <p class="text-sm text-yellow-700">Estimated wait: {{ $estimatedWaitTime }} minutes</p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="ml-4 flex flex-col gap-2">
                            <a href="{{ route('help-requests.show', $activeRequest) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-center">
                                View Details
                            </a>
                            @if($activeRequest->status === 'pending')
                                <form method="POST" action="{{ route('help-requests.destroy', $activeRequest) }}" onsubmit="return confirm('Are you sure you want to cancel this request?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                        Cancel Request
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow mb-6 p-6 text-center">
                <p class="text-gray-600 mb-4">You don't have any active requests</p>
                <a href="{{ route('help-requests.create') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    Create New Request
                </a>
            </div>
        @endif

        <!-- Recent Requests -->
        @if($requestHistory->count() > 0)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Recent Requests</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instructor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($requestHistory as $request)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $request->category->name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $request->vehicle->make }} {{ $request->vehicle->model }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $request->assignedInstructor->first_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $request->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $request->created_at->format('M d, Y') }}
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
