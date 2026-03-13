@extends('layouts.app')

@section('title', 'My Requests')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Requests</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">All your help requests history</p>
            </div>
            @if(!$requests->where('status', 'pending')->count())
                <a href="{{ route('student.requests.create') }}"
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">
                    + New Request
                </a>
            @endif
        </div>

        @if($requests->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-12 text-center">
                <div class="text-5xl mb-4">📭</div>
                <p class="text-gray-500 dark:text-gray-400 mb-4">You haven't submitted any help requests yet.</p>
                <a href="{{ route('student.requests.create') }}"
                   class="inline-block px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                    Request Help Now
                </a>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($requests as $request)
                        <a href="{{ route('student.requests.show', $request) }}"
                           class="flex items-start gap-4 p-5 hover:bg-gray-50 dark:hover:bg-gray-700 transition group">

                            {{-- Status indicator --}}
                            <div class="flex-shrink-0 mt-0.5">
                                @if($request->status === 'pending')
                                    <span class="inline-block w-3 h-3 rounded-full bg-yellow-400"></span>
                                @elseif($request->status === 'completed')
                                    <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span>
                                @else
                                    <span class="inline-block w-3 h-3 rounded-full bg-red-400"></span>
                                @endif
                            </div>

                            {{-- Details --}}
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
                                    <span>📚 {{ $request->classSection?->course_code }}</span>
                                    <span>👨‍🏫 {{ $request->assignedInstructor?->fullName() ?? 'Unassigned' }}</span>
                                    <span>📍 {{ $request->location }}</span>
                                    <span>🕐 {{ $request->created_at->format('M d, Y · g:i A') }}</span>
                                    @if($request->comments_count ?? 0)
                                        <span>💬 {{ $request->comments_count }} message{{ $request->comments_count !== 1 ? 's' : '' }}</span>
                                    @endif
                                </div>

                                {{-- Completed/Cancelled timestamps --}}
                                @if($request->status === 'completed' && $request->completed_at)
                                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                                        ✓ Completed {{ $request->completed_at->diffForHumans() }}
                                    </p>
                                @elseif($request->status === 'cancelled' && $request->cancelled_at)
                                    <p class="text-xs text-red-500 dark:text-red-400 mt-1">
                                        ✗ Cancelled {{ $request->cancelled_at->diffForHumans() }}
                                        @if($request->cancellation_reason)
                                            · "{{ Str::limit($request->cancellation_reason, 50) }}"
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
