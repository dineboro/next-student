@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Settings</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your preferences and account settings</p>
        </div>

        <div class="space-y-6">

            {{-- Notification Preferences --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Notifications</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Choose how you want to be notified</p>

                <form method="POST" action="{{ route('settings.update-notifications') }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <label class="flex items-center justify-between cursor-pointer">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Email Notifications</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Receive updates via email</p>
                            </div>
                            <div class="relative">
                                <input type="checkbox" name="email_notifications" value="1"
                                       {{ $settings->email_notifications ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:bg-blue-600 transition-colors duration-200 peer-focus:ring-2 peer-focus:ring-blue-400"></div>
                                <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-5"></div>
                            </div>
                        </label>

                        <label class="flex items-center justify-between cursor-pointer">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">SMS Notifications</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Receive text message alerts</p>
                            </div>
                            <div class="relative">
                                <input type="checkbox" name="sms_notifications" value="1"
                                       {{ $settings->sms_notifications ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:bg-blue-600 transition-colors duration-200 peer-focus:ring-2 peer-focus:ring-blue-400"></div>
                                <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-5"></div>
                            </div>
                        </label>
                    </div>

                    <div class="mt-5">
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                            Save Notifications
                        </button>
                    </div>
                </form>
            </div>

            {{-- Theme --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Appearance</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Choose your preferred theme</p>

                <form method="POST" action="{{ route('settings.update-theme') }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-3 gap-3 mb-5">
                        @foreach(['light' => ['label' => 'Light', 'icon' => '☀️'], 'dark' => ['label' => 'Dark', 'icon' => '🌙'], 'auto' => ['label' => 'Auto', 'icon' => '🖥️']] as $value => $option)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="theme" value="{{ $value }}"
                                       {{ $settings->theme === $value ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="border-2 rounded-lg p-3 text-center transition
                                    peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/30
                                    border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500">
                                    <div class="text-2xl mb-1">{{ $option['icon'] }}</div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $option['label'] }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                        Save Theme
                    </button>
                </form>
            </div>

            {{-- Profile Quick Link --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Profile</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Update your name, photo, password, and email</p>
                <a href="{{ route('profile.edit') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Edit Profile
                </a>
            </div>

            {{-- Delete Account --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border border-red-200 dark:border-red-900"
                 x-data="{ open: false }">
                <h2 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-1">Danger Zone</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Permanently delete your account and all associated data</p>

                <button @click="open = !open"
                        class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                    Delete My Account
                </button>

                <div x-show="open" x-transition class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                    <p class="text-sm text-red-700 dark:text-red-300 mb-3 font-medium">This action cannot be undone. Type DELETE to confirm.</p>
                    <form method="POST" action="{{ route('settings.delete-account') }}">
                        @csrf
                        @method('DELETE')

                        <div class="space-y-3">
                            <div>
                                <input type="password" name="password" placeholder="Current password" required
                                       class="w-full border border-red-300 dark:border-red-700 rounded-md p-2 text-sm dark:bg-gray-700 dark:text-white @error('password') border-red-500 @enderror">
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <input type="text" name="confirmation" placeholder='Type "DELETE" to confirm' required
                                       class="w-full border border-red-300 dark:border-red-700 rounded-md p-2 text-sm dark:bg-gray-700 dark:text-white">
                            </div>
                            @error('error')
                                <p class="text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                            <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                                Permanently Delete Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
