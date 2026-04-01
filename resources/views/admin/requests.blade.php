@extends('layouts.app')

@section('title', 'Help Requests — Admin')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Help Requests</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $requests->total() }} total requests</p>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="text-sm text-blue-600 dark:text-blue-400 hover:underline">← Back to Dashboard</a>
        </div>

        {{-- Filter Tabs --}}
        <div class="flex gap-2 mb-4">
            @foreach(['all' => 'All', 'active' => 'Active', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $key => $label)
                <a href="{{ route('admin.requests', ['filter' => $key]) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition
                       {{ $filter === $key
                           ? 'bg-blue-600 text-white'
                           : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 shadow' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Title</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Student</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Instructor</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($requests as $request)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-900 dark:text-white">{{ Str::limit($request->title, 50) }}</p>
                                @if($request->location)
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $request->location }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                {{ $request->student?->fullName() ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                {{ $request->assignedInstructor?->fullName() ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $colors = [
                                        'pending'   => 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300',
                                        'completed' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                        'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                    ];
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $colors[$request->status] ?? '' }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                                {{ $request->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500">No requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($requests->hasPages())
                <div class="px-4 py-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
