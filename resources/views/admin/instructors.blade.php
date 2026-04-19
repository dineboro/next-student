@extends('layouts.app')

@section('title', 'Instructors — Admin')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Instructors</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $instructors->total() }} approved instructors</p>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="text-sm text-blue-600 dark:text-blue-400 hover:underline">← Back to Dashboard</a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Name</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Email</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Department</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Instructor ID</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Joined</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($instructors as $instructor)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($instructor->profile_photo)
                                        <img src="{{ asset('storage/' . $instructor->profile_photo) }}"
                                             alt="" class="w-8 h-8 rounded-full object-cover">
                                    @elseif($instructor->badge_photo)
                                        <img src="{{ asset('storage/' . $instructor->badge_photo) }}"
                                             alt="" class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center text-white text-xs font-semibold">
                                            {{ substr($instructor->first_name, 0, 1) }}{{ substr($instructor->last_name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $instructor->fullName() }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $instructor->email }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $instructor->departmentLabel() }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $instructor->instructor_id ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $instructor->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('admin.instructors.deactivate', $instructor) }}"
                                      onsubmit="return confirm('Deactivate {{ addslashes($instructor->fullName()) }}? This will close all their sections and cancel pending requests.')">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition">
                                        Deactivate
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500">No instructors found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($instructors->hasPages())
                <div class="px-4 py-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $instructors->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
