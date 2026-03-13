@extends('layouts.app')

@section('title', 'Help Request #{{ $helpRequest->id }}')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="flex items-start justify-between mb-6">
            <div>
                <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $helpRequest->statusBadgeClass() }}">
                    {{ $helpRequest->statusLabel() }}
                </span>
                    <span class="text-xs text-gray-400">#{{ $helpRequest->id }} · {{ $helpRequest->created_at->diffForHumans() }}</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $helpRequest->title }}</h1>
            </div>

            {{-- Instructor Actions --}}
            @if(auth()->user()->role === 'instructor' && $helpRequest->assigned_instructor_id === auth()->id())
                @if($helpRequest->status === 'pending')
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('instructor.requests.complete', $helpRequest) }}">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Mark this request as complete?')"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">
                                ✓ Mark Complete
                            </button>
                        </form>
                        <button onclick="document.getElementById('cancel-modal').classList.remove('hidden')"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                            Cancel
                        </button>
                    </div>
                @endif
            @endif

            {{-- Student Actions --}}
            @if(auth()->user()->role === 'student' && $helpRequest->student_id === auth()->id())
                @if($helpRequest->status === 'pending')
                    <div class="flex gap-2">
                        <a href="{{ route('student.requests.edit', $helpRequest) }}"
                           class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            ✏️ Edit
                        </a>
                        <a href="{{ route('student.requests.cancel-form', $helpRequest) }}"
                           class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                            Cancel
                        </a>
                    </div>
                @endif
            @endif
        </div>

        {{-- Details Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6 space-y-4">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide mb-1">Student</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $helpRequest->student->fullName() }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide mb-1">Instructor</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $helpRequest->assignedInstructor?->fullName() ?? '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide mb-1">Class</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $helpRequest->classSession?->course_name }} ({{ $helpRequest->classSession?->course_code }})
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide mb-1">📍 Location</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $helpRequest->location ?? '—' }}</p>
                </div>
            </div>

            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide mb-1">Description</p>
                <p class="text-gray-800 dark:text-gray-200 text-sm leading-relaxed">{{ $helpRequest->description }}</p>
            </div>

            @if($helpRequest->image)
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide mb-2">Attached Photo</p>
                    <img src="{{ asset('storage/' . $helpRequest->image) }}"
                         alt="Request image" class="max-h-64 rounded-lg border border-gray-200 dark:border-gray-700">
                </div>
            @endif

            @if($helpRequest->resolution_notes)
                <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg p-3">
                    <p class="text-xs font-medium text-green-800 dark:text-green-300 mb-1">Resolution Notes</p>
                    <p class="text-sm text-green-700 dark:text-green-200">{{ $helpRequest->resolution_notes }}</p>
                </div>
            @endif

            @if($helpRequest->cancellation_reason)
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-lg p-3">
                    <p class="text-xs font-medium text-red-800 dark:text-red-300 mb-1">Cancellation Reason</p>
                    <p class="text-sm text-red-700 dark:text-red-200">{{ $helpRequest->cancellation_reason }}</p>
                </div>
            @endif
        </div>

        {{-- Comments / Real-time Thread --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">💬 Messages</h2>

            <div id="comments-container" class="space-y-3 mb-5 min-h-[80px]">
                @forelse($helpRequest->comments as $comment)
                    @include('partials.comment', ['comment' => $comment, 'isMe' => $comment->user_id === auth()->id()])
                @empty
                    <p id="no-comments" class="text-sm text-gray-400 text-center py-4">No messages yet. Start the conversation!</p>
                @endforelse
            </div>

            @if($helpRequest->status === 'pending')
                <form id="comment-form" class="flex gap-2">
                    @csrf
                    <input type="text" id="comment-input" placeholder="Type a message..."
                           class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                        Send
                    </button>
                </form>
            @else
                <p class="text-xs text-gray-400 text-center">This request is closed — no new messages can be sent.</p>
            @endif
        </div>
    </div>

    {{-- Instructor Cancel Modal --}}
    @if(auth()->user()->role === 'instructor' && $helpRequest->status === 'pending')
        <div id="cancel-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 w-full max-w-sm mx-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Cancel Request?</h3>
                <form method="POST" action="{{ route('instructor.requests.cancel', $helpRequest) }}">
                    @csrf
                    <textarea name="cancellation_reason" rows="3" placeholder="Reason (optional)..."
                              class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm mb-4 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                    <div class="flex gap-2">
                        <button type="button" onclick="document.getElementById('cancel-modal').classList.add('hidden')"
                                class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-2 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Back
                        </button>
                        <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm font-medium transition">
                            Confirm Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
        const POLL_URL  = "{{ route('comments.poll', $helpRequest) }}";
        const POST_URL  = "{{ route('comments.store', $helpRequest) }}";
        const CSRF      = document.querySelector('meta[name="csrf-token"]').content;
        const ME_ID     = {{ auth()->id() }};
        let lastCount   = {{ $helpRequest->comments->count() }};

        // ── Polling ──────────────────────────────────────────────────────────────────
        async function pollComments() {
            try {
                const res  = await fetch(POLL_URL, { headers: { 'Accept': 'application/json' } });
                const data = await res.json();

                if (data.length !== lastCount) {
                    lastCount = data.length;
                    renderComments(data);
                }
            } catch (e) { /* silently ignore network blips */ }
        }

        function renderComments(comments) {
            const container = document.getElementById('comments-container');
            const noMsg     = document.getElementById('no-comments');
            if (noMsg) noMsg.remove();

            container.innerHTML = comments.map(c => commentHtml(c)).join('');
            container.scrollTop = container.scrollHeight;
        }

        function commentHtml(c) {
            const isMe = c.user.is_me;
            const align = isMe ? 'items-end' : 'items-start';
            const bubble = isMe
                ? 'bg-blue-600 text-white'
                : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white';
            const role = c.user.role === 'instructor' ? '👨‍🏫 Instructor' : '🎓 Student';

            return `
        <div class="flex flex-col ${align}">
            <span class="text-xs text-gray-400 mb-1">${c.user.name} · ${role}</span>
            <div class="max-w-[80%] px-4 py-2.5 rounded-2xl text-sm ${bubble}">
                ${escapeHtml(c.message)}
            </div>
            <span class="text-xs text-gray-400 mt-1">${c.created_at}</span>
        </div>`;
        }

        function escapeHtml(str) {
            const d = document.createElement('div');
            d.appendChild(document.createTextNode(str));
            return d.innerHTML;
        }

        // ── Submit comment ────────────────────────────────────────────────────────────
        @if($helpRequest->status === 'pending')
        document.getElementById('comment-form').addEventListener('submit', async e => {
            e.preventDefault();
            const input   = document.getElementById('comment-input');
            const message = input.value.trim();
            if (!message) return;

            input.value    = '';
            input.disabled = true;

            try {
                const res  = await fetch(POST_URL, {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body:    JSON.stringify({ message }),
                });
                if (res.ok) {
                    pollComments(); // Refresh immediately after posting
                }
            } finally {
                input.disabled = false;
                input.focus();
            }
        });
        @endif

        // Poll every 5 seconds
        @if($helpRequest->status === 'pending')
        setInterval(pollComments, 5000);
        @endif
    </script>
@endsection
