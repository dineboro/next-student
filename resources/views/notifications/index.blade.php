@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $notifications->total() }} total</p>
            </div>
            @if($notifications->isNotEmpty())
                <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                    @csrf
                    <button type="submit"
                            class="text-sm px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>

        <div class="space-y-2">
            @forelse($notifications as $notification)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 flex items-start gap-4
                    {{ !$notification->is_read ? 'ring-1 ring-blue-400 dark:ring-blue-600' : '' }}">
                    {{-- Icon --}}
                    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                        {{ !$notification->is_read ? 'bg-blue-100 dark:bg-blue-900' : 'bg-gray-100 dark:bg-gray-700' }}">
                        @php
                            $icons = [
                                'request_assigned'  => '👨‍🏫',
                                'request_completed' => '✅',
                                'request_cancelled' => '❌',
                                'new_comment'       => '💬',
                                'approval'          => '✔️',
                            ];
                        @endphp
                        <span class="text-lg">{{ $icons[$notification->type] ?? '🔔' }}</span>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $notification->title }}
                            @if(!$notification->is_read)
                                <span class="inline-block w-2 h-2 bg-blue-500 rounded-full ml-1 align-middle"></span>
                            @endif
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">{{ $notification->message }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-12 text-center">
                    <div class="text-5xl mb-3">🔔</div>
                    <p class="text-gray-500 dark:text-gray-400">You have no notifications yet.</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
@endsection
