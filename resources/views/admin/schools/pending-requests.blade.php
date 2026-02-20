@extends('layouts.app')

@section('title', 'Pending School Registrations')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Pending School Registrations</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Review and approve school registration requests</p>
        </div>

        @if($requests->count() > 0)
            <div class="space-y-6">
                @foreach($requests as $request)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $request->school_name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Requested {{ $request->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                            Pending
                        </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">School Information</h4>
                                <dl class="space-y-1 text-sm">
                                    <div>
                                        <dt class="inline font-medium text-gray-900 dark:text-white">Domain:</dt>
                                        <dd class="inline text-gray-600 dark:text-gray-400">{{ $request->school_domain }}</dd>
                                    </div>
                                    <div>
                                        <dt class="inline font-medium text-gray-900 dark:text-white">Registration ID:</dt>
                                        <dd class="inline text-gray-600 dark:text-gray-400">{{ $request->registration_id }}</dd>
                                    </div>
                                    <div>
                                        <dt class="inline font-medium text-gray-900 dark:text-white">Contact:</dt>
                                        <dd class="inline text-gray-600 dark:text-gray-400">{{ $request->contact_info }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Requester Information</h4>
                                <dl class="space-y-1 text-sm">
                                    <div>
                                        <dt class="inline font-medium text-gray-900 dark:text-white">Name:</dt>
                                        <dd class="inline text-gray-600 dark:text-gray-400">{{ $request->requester_name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="inline font-medium text-gray-900 dark:text-white">Email:</dt>
                                        <dd class="inline text-gray-600 dark:text-gray-400">{{ $request->requester_email }}</dd>
                                    </div>
                                    @if($request->requester_phone)
                                        <div>
                                            <dt class="inline font-medium text-gray-900 dark:text-white">Phone:</dt>
                                            <dd class="inline text-gray-600 dark:text-gray-400">{{ $request->requester_phone }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Address</h4>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $request->address }}</p>
                        </div>

                        <div class="flex gap-3">
                            <form method="POST" action="{{ route('admin.schools.approve', $request) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('Approve this school registration?')"
                                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                    ✓ Approve School
                                </button>
                            </form>

                            <button onclick="showRejectModal({{ $request->id }})"
                                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                                ✗ Reject
                            </button>
                        </div>

                        <!-- Reject Modal -->
                        <div id="reject-modal-{{ $request->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Reject School Registration</h3>
                                <form method="POST" action="{{ route('admin.schools.reject', $request) }}">
                                    @csrf
                                    <textarea name="rejection_reason" rows="4" required placeholder="Provide a reason for rejection..."
                                              class="w-full border border-gray-300 dark:border-gray-600 rounded p-2 dark:bg-gray-700 dark:text-white mb-4"></textarea>
                                    <div class="flex gap-3">
                                        <button type="button" onclick="hideRejectModal({{ $request->id }})"
                                                class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-700">
                                            Cancel
                                        </button>
                                        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                            Reject
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $requests->links() }}
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="mt-4 text-gray-500 dark:text-gray-400">No pending school registrations</p>
            </div>
        @endif
    </div>

    <script>
        function showRejectModal(id) {
            document.getElementById('reject-modal-' + id).classList.remove('hidden');
        }
        function hideRejectModal(id) {
            document.getElementById('reject-modal-' + id).classList.add('hidden');
        }
    </script>
@endsection
