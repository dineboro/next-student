@extends('layouts.app')

@section('title', 'Pending Instructor Approvals')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pending Instructor Approvals</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Review badge photos and approve or reject instructor accounts</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 rounded-lg p-3 mb-4 text-sm text-green-800 dark:text-green-200">{{ session('success') }}</div>
        @endif

        @if($users->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-12 text-center">
                <div class="text-5xl mb-3">✅</div>
                <p class="text-gray-500 dark:text-gray-400">No pending approvals. All instructors are reviewed.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($users as $user)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
                        <div class="flex gap-5">
                            {{-- Badge Photo --}}
                            <div class="flex-shrink-0">
                                @if($user->badge_photo)
                                    <a href="{{ asset('storage/' . $user->badge_photo) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $user->badge_photo) }}"
                                             alt="Badge photo"
                                             class="w-24 h-24 object-cover rounded-lg border-2 border-blue-200 dark:border-blue-700 hover:opacity-80 transition cursor-zoom-in">
                                    </a>
                                    <p class="text-xs text-center text-gray-400 mt-1">Click to enlarge</p>
                                @else
                                    <div class="w-24 h-24 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <span class="text-gray-400 text-xs">No photo</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-semibold text-gray-900 dark:text-white text-lg">{{ $user->fullName() }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->phone_number }}</p>
                                    </div>
                                    <div class="text-right">
                                <span class="inline-block px-2.5 py-1 text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 rounded-full">
                                    {{ $user->departmentLabel() }}
                                </span>
                                        <p class="text-xs text-gray-400 mt-1">Registered {{ $user->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                <div class="flex gap-2 mt-4">
                                    {{-- Approve --}}
                                    <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('Approve {{ $user->fullName() }} as instructor?')"
                                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">
                                            ✓ Approve
                                        </button>
                                    </form>

                                    {{-- Reject --}}
                                    <button onclick="document.getElementById('reject-{{ $user->id }}').classList.toggle('hidden')"
                                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                                        ✗ Reject
                                    </button>
                                </div>

                                {{-- Reject form (inline) --}}
                                <div id="reject-{{ $user->id }}" class="hidden mt-3">
                                    <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="flex gap-2">
                                        @csrf
                                        <input type="text" name="rejection_reason" required
                                               placeholder="Reason for rejection..."
                                               class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:outline-none">
                                        <button type="submit"
                                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                                            Send Rejection
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
