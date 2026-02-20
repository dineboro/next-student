@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Settings</h1>
        </div>

        <div class="space-y-6">
            <!-- Notification Preferences -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Notification Preferences</h2>

                <form method="POST" action="{{ route('settings.update-notifications') }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium text-gray-900 dark:text-white">Email Notifications</label>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Receive notifications via email</p>
                            </div>
                            <input type="checkbox" name="notify_email" value="1" {{ $settings->notify_email ? 'checked' : '' }}
                            class="h-5 w-5 text-blue-600 rounded">
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium text-gray-900 dark:text-white">SMS Notifications</label>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Receive notifications via text message</p>
                            </div>
                            <input type="checkbox" name="notify_sms" value="1" {{ $settings->notify_sms ? 'checked' : '' }}
                            class="h-5 w-5 text-blue-600 rounded">
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium text-gray-900 dark:text-white">Push Notifications</label>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Receive push notifications in browser</p>
                            </div>
                            <input type="checkbox" name="notify_push" value="1" {{ $settings->notify_push ? 'checked' : '' }}
                            class="h-5 w-5 text-blue-600 rounded">
                        </div>

                        <hr class="border-gray-200 dark:border-gray-700">

                        <p class="font-medium text-gray-900 dark:text-white">Notify me when:</p>

                        <div class="flex items-center justify-between pl-4">
                            <label class="text-gray-900 dark:text-white">Request is assigned to me</label>
                            <input type="checkbox" name="notify_request_assigned" value="1" {{ $settings->notify_request_assigned ? 'checked' : '' }}
                            class="h-5 w-5 text-blue-600 rounded">
                        </div>

                        <div class="flex items-center justify-between pl-4">
                            <label class="text-gray-900 dark:text-white">Request status changes</label>
                            <input type="checkbox" name="notify_request_updated" value="1" {{ $settings->notify_request_updated ? 'checked' : '' }}
                            class="h-5 w-5 text-blue-600 rounded">
                        </div>

                        <div class="flex items-center justify-between pl-4">
                            <label class="text-gray-900 dark:text-white">Request is completed</label>
                            <input type="checkbox" name="notify_request_completed" value="1" {{ $settings->notify_request_completed ? 'checked' : '' }}
                            class="h-5 w-5 text-blue-600 rounded">
                        </div>

                        <div class="flex items-center justify-between pl-4">
                            <label class="text-gray-900 dark:text-white">New comment is added</label>
                            <input type="checkbox" name="notify_new_comment" value="1" {{ $settings->notify_new_comment ? 'checked' : '' }}
                            class="h-5 w-5 text-blue-600 rounded">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Save Preferences
                        </button>
                    </div>
                </form>
            </div>

            <!-- Privacy Settings -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Privacy Settings</h2>

                <form method="POST" action="{{ route('settings.update-privacy') }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium text-gray-900 dark:text-white">Profile Visible</label>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Allow others to see your profile</p>
                            </div>
                            <input type="checkbox" name="profile_visible" value="1" {{ $settings->profile_visible ? 'checked' : '' }}
                            class="h-5 w-5 text-blue-600 rounded">
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium text-gray-900 dark:text-white">Show Email</label>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Display email on your profile</p>
                            </div>
                            <input type="checkbox" name="show_email" value="1" {{ $settings->show_email ? 'checked' : '' }}
                            class="h-5 w-5 text-blue-600 rounded">
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium text-gray-900 dark:text-white">Show Phone</label>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Display phone number on your profile</p>
                            </div>
                            <input type="checkbox" name="show_phone" value="1" {{ $settings->show_phone ? 'checked' : '' }}
                            class="h-5 w-5 text-blue-600 rounded">
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium text-gray-900 dark:text-white">Allow Messages</label>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Allow others to send you messages</p>
                            </div>
                            <input type="checkbox" name="allow_messages" value="1" {{ $settings->allow_messages ? 'checked' : '' }}
                            class="h-5 w-5 text-blue-600 rounded">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Save Privacy Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- Delete Account -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border-2 border-red-200 dark:border-red-900">
                <h2 class="text-xl font-semibold text-red-600 dark:text-red-400 mb-4">Danger Zone</h2>

                <form method="POST" action="{{ route('settings.delete-account') }}"
                      onsubmit="return confirm('Are you absolutely sure? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')

                    <div class="space-y-4">
                        <div class="p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded">
                            <p class="text-sm text-red-800 dark:text-red-200">
                                <strong>Warning:</strong> Deleting your account is permanent and cannot be undone.
                                All your data will be permanently deleted.
                            </p>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Enter your password to confirm *
                            </label>
                            <input type="password" name="password" id="password" required
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 dark:bg-gray-700 dark:text-white @error('password') border-red-500 @enderror">
                            @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Type "DELETE" to confirm *
                            </label>
                            <input type="text" name="confirmation" id="confirmation" required placeholder="DELETE"
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 dark:bg-gray-700 dark:text-white @error('confirmation') border-red-500 @enderror">
                            @error('confirmation')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @error('error')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Delete My Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
