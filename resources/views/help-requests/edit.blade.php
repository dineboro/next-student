@extends('layouts.app')

@section('title', 'Edit Help Request')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('student.requests.show', $helpRequest) }}"
               class="text-sm text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                ← Back to Request
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Edit Help Request</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                You can edit your request while it is still pending.
            </p>
        </div>

        <form method="POST"
              action="{{ route('student.requests.update', $helpRequest) }}"
              enctype="multipart/form-data"
              class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Class Section (read-only display) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Class Section
                </label>
                <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                    📚 {{ $helpRequest->classSection?->course_name }}
                    ({{ $helpRequest->classSection?->course_code }})
                    — {{ $helpRequest->classSection?->semesterLabel() }}
                </div>
                <p class="text-xs text-gray-400 mt-1">Class section cannot be changed after submission.</p>
            </div>

            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Title *
                </label>
                <input type="text" name="title"
                       value="{{ old('title', $helpRequest->title) }}"
                       required
                       placeholder="e.g. Need help understanding SQL joins"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('title') border-red-500 @enderror">
                @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Description *
                </label>
                <textarea name="description" rows="4" required
                          placeholder="Describe what you need help with..."
                          class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('description') border-red-500 @enderror">{{ old('description', $helpRequest->description) }}</textarea>
                @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Location --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Your Location *
                </label>
                <input type="text" name="location"
                       value="{{ old('location', $helpRequest->location) }}"
                       required
                       placeholder="e.g. Room 204, Library, Cafeteria, Workstation 12..."
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('location') border-red-500 @enderror">
                @error('location')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Current Image --}}
            @if($helpRequest->image)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Current Photo
                    </label>
                    <img src="{{ asset('storage/' . $helpRequest->image) }}"
                         alt="Current request image"
                         class="max-h-40 rounded-lg border border-gray-200 dark:border-gray-700 mb-2">
                    <p class="text-xs text-gray-400">Upload a new photo below to replace it.</p>
                </div>
            @endif

            {{-- New Image Upload --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ $helpRequest->image ? 'Replace Photo' : 'Attach a Photo' }}
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
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Click to upload a photo</p>
                    <p class="text-xs text-gray-400">JPG, PNG up to 5MB</p>
                </div>
                <input type="file" id="req-image" name="image" accept="image/*" class="hidden"
                       onchange="previewImage(this)">
                @error('image')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('student.requests.show', $helpRequest) }}"
                   class="flex-1 text-center border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium py-2.5 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg text-sm transition">
                    Save Changes
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
