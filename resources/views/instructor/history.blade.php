@extends('layouts.app')

@section('title', 'Request History')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Request History</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">All completed and cancelled requests</p>
            </div>
            <a href="{{ route('instructor.dashboard') }}"
               class="text-sm text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                ← Dashboard
            </a>
        </div>

        @if($requests->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-12 text-center">
                <div class="text-5xl mb-4">📋</div>
                <p class="text-gray-500 dark:text-gray-400">No completed or cancelled requests yet.</p>
            </div>
        @else
            {{-- Summary Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                        {{ $requests->where('status', 'completed')->count() }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Completed</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-red-500 dark:text-red-400">
                        {{ $requests->where('status', 'cancelled')->count() }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Cancelled</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center col-span-2 md:col-span-1">
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $requests->total() }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($requests as $request)
                        <a href="{{ route('instructor.requests.show', $request) }}"
                           class="flex items-start gap-4 p-5 hover:bg-gray-50 dark:hover:bg-gray-700 transition group">

                            {{-- Status dot --}}
                            <div class="flex-shrink-0 mt-1">
                                @if($request->status === 'completed')
                                    <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span>
                                @else
                                    <span class="inline-block w-3 h-3 rounded-full bg-red-400"></span>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                                            {{ $request->title }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1">
                                            {{ $request->description }}
                                        </p>
                                    </div>
                                    <span class="flex-shrink-0 px-2.5 py-1 text-xs font-semibold rounded-full {{ $request->statusBadgeClass() }}">
                                {{ $request->statusLabel() }}
                            </span>
                                </div>

                                <div class="flex flex-wrap gap-4 mt-2 text-xs text-gray-400">
                                    <span>🎓 {{ $request->student->fullName() }}</span>
                                    <span>📚 {{ $request->classSection?->course_code }}</span>
                                    <span>📍 {{ $request->location }}</span>
                                    <span>🕐 {{ $request->created_at->format('M d, Y') }}</span>
                                </div>

                                @if($request->status === 'completed' && $request->completed_at)
                                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                                        ✓ Completed {{ $request->completed_at->diffForHumans() }}
                                    </p>
                                    @if($request->resolution_notes)
                                        <p class="text-xs text-gray-400 mt-0.5 italic">
                                            "{{ Str::limit($request->resolution_notes, 80) }}"
                                        </p>
                                    @endif
                                @elseif($request->status === 'cancelled' && $request->cancelled_at)
                                    <p class="text-xs text-red-500 dark:text-red-400 mt-1">
                                        ✗ Cancelled {{ $request->cancelled_at->diffForHumans() }}
                                        @if($request->cancellation_reason)
                                            · "{{ Str::limit($request->cancellation_reason, 60) }}"
                                        @endif
                                    </p>
                                @endif
                            </div>

                            {{-- Arrow --}}
                            <div class="flex-shrink-0 text-gray-300 dark:text-gray-600 group-hover:text-blue-400 transition mt-1">
                                →
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Pagination --}}
            @if($requests->hasPages())
                <div class="mt-4">
                    {{ $requests->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
