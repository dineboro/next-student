@extends('layouts.app')

@section('title', isset($section) ? 'Edit section' : 'Create section')

@section('content')
    <div class="max-w-xl mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('instructor.sections.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-sm">← Back to sections</a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                {{ isset($section) ? 'Edit section' : 'Create New section' }}
            </h1>
        </div>

        <form method="POST"
              action="{{ isset($section) ? route('instructor.sections.update', $section) : route('instructor.sections.store') }}"
              class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 space-y-5">
            @csrf
            @if(isset($section)) @method('PUT') @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course Name *</label>
                <input type="text" name="course_name"
                       value="{{ old('course_name', $section->course_name ?? '') }}" required
                       placeholder="e.g. Introduction to Web Development"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('course_name') border-red-500 @enderror">
                @error('course_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course Code *</label>
                <input type="text" name="course_code"
                       value="{{ old('course_code', $section->course_code ?? '') }}" required
                       placeholder="e.g. CIS-110"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('course_code') border-red-500 @enderror">
                @error('course_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Semester *</label>
                    <select name="semester" required
                            class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('semester') border-red-500 @enderror">
                        @foreach(['Fall', 'Spring', 'Summer'] as $sem)
                            <option value="{{ $sem }}" {{ old('semester', $section->semester ?? '') === $sem ? 'selected' : '' }}>
                                {{ $sem }}
                            </option>
                        @endforeach
                    </select>
                    @error('semester')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Year *</label>
                    <select name="year" required
                            class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('year') border-red-500 @enderror">
                        @for($y = now()->year; $y <= now()->year + 2; $y++)
                            <option value="{{ $y }}" {{ old('year', $section->year ?? now()->year) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                    @error('year')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            @if(isset($section))
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', $section->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        section is Active
                        <span class="font-normal text-gray-400">(students can submit help requests)</span>
                    </label>
                </div>
            @endif

            <div class="flex gap-3 pt-2">
                <a href="{{ route('instructor.sections.index') }}"
                   class="flex-1 text-center border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-2.5 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg text-sm transition">
                    {{ isset($section) ? 'Save Changes' : 'Create section' }}
                </button>
            </div>
        </form>
    </div>
@endsection
