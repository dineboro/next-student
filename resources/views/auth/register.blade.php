@extends('layouts.app')

@section('title', 'Register — Kirkwood Help')

@section('content')
    <div class="max-w-lg mx-auto py-10 px-4">
        <div class="text-center mb-8">
            <img src="https://www.kirkwood.edu/_files/img/Kirkwood_logo_new_v2.svg" alt="Kirkwood Community College" class="h-20 w-auto mx-auto mb-4 opacity-100">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white"></h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kirkwood Community College Help System</p>
        </div>

        {{-- Role Tabs --}}
        <div class="flex rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
            <button type="button" id="tab-student"
                    onclick="switchRole('student')"
                    class="flex-1 py-3 text-sm font-semibold transition role-tab active-tab">
                🎓 I'm a Student
            </button>
            <button type="button" id="tab-instructor"
                    onclick="switchRole('instructor')"
                    class="flex-1 py-3 text-sm font-semibold transition role-tab">
                👨‍🏫 I'm an Instructor
            </button>
        </div>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data"
              class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 space-y-5">
            @csrf
            <input type="hidden" name="role" id="role-input" value="student">

            {{-- Name Row --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required
                           class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('first_name') border-red-500 @enderror">
                    @error('first_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required
                           class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('last_name') border-red-500 @enderror">
                    @error('last_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kirkwood Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       placeholder="yourname@kirkwood.edu"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('email') border-red-500 @enderror">
                @error('email')<p class="text-red-500 text-xs mt-1">{!! $message !!}</p>@enderror
            </div>

            {{-- Phone --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number *
                    <span class="font-normal text-gray-400">(for SMS notifications)</span>
                </label>
                <input type="tel" name="phone_number" value="{{ old('phone_number') }}" required
                       placeholder="(319) 555-0100"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('phone_number') border-red-500 @enderror">
                @error('phone_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- STUDENT: Major --}}
            <div id="field-major">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Academic Major *</label>
                <input type="text" name="major" value="{{ old('major') }}"
                       placeholder="e.g. Web Application Development, Nursing, Business..."
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('major') border-red-500 @enderror">
                @error('major')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- INSTRUCTOR: Department --}}
            <div id="field-department" class="hidden">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department *</label>
                <select name="department"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('department') border-red-500 @enderror">
                    <option value="">Select your department</option>
                    <option value="business_it"       {{ old('department') === 'business_it'       ? 'selected' : '' }}>Business & IT</option>
                    <option value="liberal_arts"      {{ old('department') === 'liberal_arts'      ? 'selected' : '' }}>Liberal Arts</option>
                    <option value="science"           {{ old('department') === 'science'           ? 'selected' : '' }}>Science</option>
                    <option value="health_sciences"   {{ old('department') === 'health_sciences'   ? 'selected' : '' }}>Health Sciences</option>
                    <option value="trades_technology" {{ old('department') === 'trades_technology' ? 'selected' : '' }}>Trades & Technology</option>
                    <option value="arts_humanities"   {{ old('department') === 'arts_humanities'   ? 'selected' : '' }}>Arts & Humanities</option>
                </select>
                @error('department')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- INSTRUCTOR: Badge Photo --}}
            <div id="field-badge" class="hidden">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Badge / ID Photo *
                    <span class="font-normal text-gray-400">(for admin verification)</span>
                </label>
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center cursor-pointer hover:border-blue-400 transition"
                     onclick="document.getElementById('badge_photo').click()">
                    <div id="badge-preview-wrap" class="hidden mb-3">
                        <img id="badge-preview" src="" alt="Badge preview" class="max-h-32 mx-auto rounded">
                    </div>
                    <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Click to upload badge photo</p>
                    <p class="text-xs text-gray-400">JPG, PNG up to 4MB</p>
                </div>
                <input type="file" id="badge_photo" name="badge_photo" accept="image/*" class="hidden"
                       onchange="previewBadge(this)">
                @error('badge_photo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password *</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('password') border-red-500 @enderror">
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password *</label>
                <input type="password" name="password_confirmation" required
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            {{-- Instructor note --}}
            <div id="instructor-note" class="hidden bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-3 text-sm text-blue-800 dark:text-blue-200">
                📋 After verifying your email, your account will be reviewed by an admin. You'll receive an SMS once approved.
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition text-sm">
                Create Account
            </button>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Sign in</a>
            </p>
        </form>
    </div>

    <style>
        .role-tab { background: #f9fafb; color: #374151; }
        .dark .role-tab { background: #1f2937; color: #d1d5db; }
        .active-tab { background: #2563eb !important; color: #ffffff !important; }
    </style>

    <script>
        function switchRole(role) {
            document.getElementById('role-input').value = role;

            document.getElementById('tab-student').classList.toggle('active-tab', role === 'student');
            document.getElementById('tab-instructor').classList.toggle('active-tab', role === 'instructor');

            document.getElementById('field-major').classList.toggle('hidden', role !== 'student');
            document.getElementById('field-department').classList.toggle('hidden', role !== 'instructor');
            document.getElementById('field-badge').classList.toggle('hidden', role !== 'instructor');
            document.getElementById('instructor-note').classList.toggle('hidden', role !== 'instructor');
        }

        function previewBadge(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('badge-preview').src = e.target.result;
                    document.getElementById('badge-preview-wrap').classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Restore role tab state after validation errors
        @if(old('role') === 'instructor')
        switchRole('instructor');
        @endif
    </script>
@endsection
