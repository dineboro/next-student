@php
    $isMe = $comment->user_id === auth()->id();
@endphp

<div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
    <span class="text-xs text-gray-400 mb-1">
        {{ $comment->user->fullName() }}
        · {{ $comment->user->role === 'instructor' ? '👨‍🏫 Instructor' : '🎓 Student' }}
    </span>
    <div class="max-w-[80%] px-4 py-2.5 rounded-2xl text-sm
        {{ $isMe
            ? 'bg-blue-600 text-white'
            : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' }}">
        {{ $comment->message }}
    </div>
    <span class="text-xs text-gray-400 mt-1">{{ $comment->created_at->diffForHumans() }}</span>
</div>
