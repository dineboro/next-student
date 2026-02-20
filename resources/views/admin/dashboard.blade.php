@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage users, schools, and approvals</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900 rounded-md p-3">
                        <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Pending Users</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_users'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900 rounded-md p-3">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Pending Schools</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_schools'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900 rounded-md p-3">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-md p-3">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Total Schools</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_schools'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-orange-100 dark:bg-orange-900 rounded-md p-3">
                        <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Active Requests</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['active_requests'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <a href="{{ route('admin.users.pending') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">👥 Pending User Approvals</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Review and approve new user registrations</p>
                @if($stats['pending_users'] > 0)
                    <span class="inline-block mt-3 px-3 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 text-sm font-semibold rounded-full">
                    {{ $stats['pending_users'] }} pending
                </span>
                @endif
            </a>

            <a href="{{ route('admin.school-requests.pending') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">🏫 Pending School Registrations</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Review and approve new school registrations</p>
                @if($stats['pending_schools'] > 0)
                    <span class="inline-block mt-3 px-3 py-1 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 text-sm font-semibold rounded-full">
                    {{ $stats['pending_schools'] }} pending
                </span>
                @endif
            </a>
        </div>

        <!-- Recent Pending Users -->
        @if($recentUsers->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Recent Pending Users</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">School</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Registered</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($recentUsers as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <span class="capitalize">{{ $user->role }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $user->school->school_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $user->created_at->diffForHumans() }}
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
