@extends('layouts.app')

@section('title', 'Instructors')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Instructors</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">View all instructors at your school</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900 rounded-md p-3">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Total Instructors</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-md p-3">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Available Now</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['available'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-100 dark:bg-red-900 rounded-md p-3">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Unavailable</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['busy'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
            <form method="GET" action="{{ route('instructor.instructors-list') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by name or email..."
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 dark:bg-gray-700 dark:text-white">
                </div>
                <select name="availability"
                        class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 dark:bg-gray-700 dark:text-white">
                    <option value="">All Availability</option>
                    <option value="available" {{ request('availability') === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ request('availability') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
                <select name="sort"
                        class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 dark:bg-gray-700 dark:text-white">
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Sort by Name</option>
                    <option value="availability" {{ request('sort') === 'availability' ? 'selected' : '' }}>Sort by Availability</option>
                    <option value="workload" {{ request('sort') === 'workload' ? 'selected' : '' }}>Sort by Workload</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Apply Filters
                </button>
                <a href="{{ route('instructor.instructors-list') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-50 dark:hover:bg-gray-700">
                    Clear
                </a>
            </form>
        </div>

        <!-- Instructors Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($instructors as $instructor)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 rounded-full bg-blue-600 flex items-center justify-center text-white text-xl font-bold">
                                {{ substr($instructor->first_name, 0, 1) }}{{ substr($instructor->last_name, 0, 1) }}
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $instructor->first_name }} {{ $instructor->last_name }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $instructor->instructor_id }}</p>
                            </div>
                        </div>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400">{{ $instructor->email }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400">{{ $instructor->phone_number }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded {{ $instructor->is_available ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ $instructor->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $instructor->active_requests_count }} active
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('instructor.instructors.show', $instructor) }}"
                               class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                View Profile
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="mt-4 text-gray-500 dark:text-gray-400">No instructors found</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $instructors->links() }}
        </div>
    </div>
@endsection
