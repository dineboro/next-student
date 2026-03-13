@extends('layouts.app')

@section('title', 'Request Help')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Request Help</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Your instructor will be notified by SMS instantly.</p>
        </div>

        <form method="POST" action="{{ route('student.requests.store') }}" enctype="multipart/form-data"
              class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 space-y-5">
            @csrf

            {{-- Class Session --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class Session *</label>
                @if($sessions->count() === 1)
                    <input type="hidden" name="class_section_id" value="{{ $sessions->first()->id }}">
                    <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-4 py-3 text-sm text-gray-800 dark:text-gray-200">
                        📚 {{ $sessions->first()->course_name }} ({{ $sessions->first()->course_code }})
                        — {{ $sessions->first()->semesterLabel() }}
                        <span class="text-gray-500 dark:text-gray-400">· Instructor: {{ $sessions->first()->instructor->fullName() }}</span>
                    </div>
                @else
                    <select name="class_section_id" required
                            class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('class_section_id') border-red-500 @enderror">
                        <option value="">Select a class</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}" {{ old('class_section_id') == $session->id ? 'selected' : '' }}>
                                {{ $session->course_name }} ({{ $session->course_code }}) — {{ $session->semesterLabel() }}
                                · {{ $session->instructor->fullName() }}
                            </option>
                        @endforeach
                    </select>
                    @error('class_section_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                @endif
            </div>

            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       placeholder="e.g. Need help understanding SQL joins"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('title') border-red-500 @enderror">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description *</label>
                <textarea name="description" rows="4" required
                          placeholder="Describe what you need help with in detail..."
                          class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Location --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Your Location *</label>
                <input type="text" name="location" value="{{ old('location') }}" required
                       placeholder="e.g. Room 204, Library, Cafeteria, Workstation 12..."
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('location') border-red-500 @enderror">
                @error('location')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Image (optional) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Attach a Photo
                    <span class="font-normal text-gray-400">(optional)</span>
                </label>
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center cursor-pointer hover:border-blue-400 transition"
                     onclick="document.getElementById('req-image').click()">
                    <div id="img-preview-wrap" class="hidden mb-3">
                        <img id="img-preview" src="" alt="Preview" class="max-h-40 mx-auto rounded">
                    </div>
                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Click to add a photo</p>
                </div>
                <input type="file" id="req-image" name="image" accept="image/*" class="hidden"
                       onchange="previewImage(this)">
                @error('image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('student.dashboard') }}"
                   class="flex-1 text-center border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium py-2.5 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg text-sm transition">
                    Submit Request
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('img-preview').src = e.target.result;
                    document.getElementById('img-preview-wrap').classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
