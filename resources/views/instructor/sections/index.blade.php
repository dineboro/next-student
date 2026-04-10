@extends('layouts.app')

@section('title', 'My Class sections')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Class sections</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your sections and student rosters</p>
            </div>
            <a href="{{ route('instructor.sections.create') }}"
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">
                + New section
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg p-3 mb-4 text-sm text-green-800 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if($sections->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-12 text-center">
                <div class="text-5xl mb-4">📚</div>
                <p class="text-gray-500 dark:text-gray-400">No sections yet. Create your first section to get started.</p>
                <a href="{{ route('instructor.sections.create') }}"
                   class="inline-block mt-4 px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                    Create section
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($sections as $section)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center text-xl
                        {{ $section->is_active ? 'bg-green-100 dark:bg-green-900' : 'bg-gray-100 dark:bg-gray-700' }}">
                                {{ $section->is_active ? '🟢' : '⚪' }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $section->course_name }}</p>
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ $section->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                {{ $section->is_active ? 'Active' : 'Inactive' }}
                            </span>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $section->course_code }} · {{ $section->semesterLabel() }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $section->students_count }} student{{ $section->students_count !== 1 ? 's' : '' }}
                                    @if($section->pending_requests_count > 0)
                                        · <span class="text-yellow-600 dark:text-yellow-400 font-medium">{{ $section->pending_requests_count }} pending request{{ $section->pending_requests_count !== 1 ? 's' : '' }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('instructor.sections.show', $section) }}"
                               class="px-3 py-1.5 text-xs font-medium border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                Manage
                            </a>
                            <a href="{{ route('instructor.sections.edit', $section) }}"
                               class="px-3 py-1.5 text-xs font-medium border border-blue-300 text-blue-600 dark:border-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30 transition">
                                Edit
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
