@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Profile</h1>
        </div>

        <div class="space-y-6">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h2>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                First Name *
                            </label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 dark:bg-gray-700 dark:text-white @error('first_name') border-red-500 @enderror">
                            @error('first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Last Name *
                            </label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 dark:bg-gray-700 dark:text-white @error('last_name') border-red-500 @enderror">
                            @error('last_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Phone Number *
                            </label>
                            <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 dark:bg-gray-700 dark:text-white @error('phone_number') border-red-500 @enderror">
                            @error('phone_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Profile Photo -->
                        <div>
                            <label for="profile_photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Profile Photo
                            </label>
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 dark:bg-gray-700 dark:text-white @error('profile_photo') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Max 2MB</p>
                            @error('profile_photo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Bio -->
                    <div class="mt-6">
                        <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Bio
                        </label>
                        <textarea name="bio" id="bio" rows="4" maxlength="500"
                                  class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 dark:bg-gray-700 dark:text-white @error('bio') border-red-500 @enderror">{{ old('bio', $user->bio) }}</textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maximum 500 characters</p>
                        @error('bio')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('profile.show') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Change Password</h2>

                <form method="POST" action="{{ route('profile.update-password') }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Current Password *
                            </label>
                            <input type="password" name="current_password" id="current_password" required
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 dark:bg-gray-700 dark:text-white @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                New Password *
                            </label>
                            <input type="password" name="password" id="password" required
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 dark:bg-gray-700 dark:text-white @error('password') border-red-500 @enderror">
                            @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Confirm New Password *
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Email -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Change Email</h2>

                <form method="POST" action="{{ route('profile.update-email') }}">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            New Email *
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Must use your school domain: @{{ $user->school->school_domain }}
                        </p>
                        @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded">
                        <p class="text-xs text-yellow-800 dark:text-yellow-200">
                            ⚠️ Changing your email will require you to verify the new email address.
                        </p>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Update Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
