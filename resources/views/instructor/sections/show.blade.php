@extends('layouts.app')

@section('title', 'Section')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('instructor.sections.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                ← Back
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $section->course_name }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $section->course_code }} · {{ $section->semesterLabel() }}
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $section->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $section->is_active ? 'Active' : 'Inactive' }}
                </span>
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 rounded-lg p-3 mb-4 text-sm text-green-800 dark:text-green-200">{{ session('success') }}</div>
        @endif
        @if(session('info'))
            <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 rounded-lg p-3 mb-4 text-sm text-blue-800 dark:text-blue-200">{{ session('info') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Add Student --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-3">Add Student</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Search by K-number, name, or email</p>

                <div class="relative mb-3">
                    <input type="text" id="student-search" placeholder="Search students..."
                           class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                           autocomplete="off">
                    <div id="search-results"
                         class="hidden absolute z-10 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto">
                    </div>
                </div>

                {{-- Selected student to add --}}
                <form id="add-student-form" method="POST" action="{{ route('instructor.sections.students.add', $section) }}">
                    @csrf
                    <input type="hidden" name="student_id" id="selected-student-id">
                    <div id="selected-student" class="hidden bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-3 mb-3">
                        <p class="text-sm font-medium text-blue-900 dark:text-blue-100" id="selected-student-name"></p>
                        <p class="text-xs text-blue-600 dark:text-blue-300" id="selected-student-detail"></p>
                    </div>
                    <button type="submit" id="add-btn" disabled
                            class="w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-40 disabled:cursor-not-allowed text-white text-sm font-medium py-2 rounded-lg transition">
                        Add to Roster
                    </button>
                </form>
            </div>

            {{-- Roster --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-3">
                    Roster
                    <span class="ml-1 text-sm font-normal text-gray-400">({{ $section->students->count() }})</span>
                </h2>

                @if($section->students->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-8">No students added yet.</p>
                @else
                    <div class="space-y-2 max-h-80 overflow-y-auto">
                        @foreach($section->students as $student)
                            <div class="flex items-center justify-between p-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $student->fullName() }}</p>
                                    <p class="text-xs text-gray-400">{{ $student->student_id }} · {{ $student->email }}</p>
                                </div>
                                <form method="POST" action="{{ route('instructor.sections.students.remove', [$section, $student]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Remove {{ $student->fullName() }} from this section?')"
                                            class="text-xs text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/30 px-2 py-1 rounded transition">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        const SEARCH_URL = "{{ route('instructor.sections.search-student') }}";
        let debounceTimer;

        document.getElementById('student-search').addEventListener('input', function () {
            clearTimeout(debounceTimer);
            const q = this.value.trim();
            console.log(q)
            if (q.length < 2) {
                document.getElementById('search-results').classList.add('hidden');
                return;
            }
            debounceTimer = setTimeout(() => searchStudents(q), 300);
        });

        async function searchStudents(q) {
            const res     = await fetch(`${SEARCH_URL}?query=${encodeURIComponent(q)}`, {
                headers: { 'Accept': 'application/json' }
            });
            const results = await res.json();
            const box     = document.getElementById('search-results');

            if (results.length === 0) {
                box.innerHTML = '<p class="text-sm text-gray-400 p-3 text-center">No students found</p>';
            } else {
                box.innerHTML = results.map(s => `
            <div class="px-3 py-2.5 cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/30 border-b border-gray-100 dark:border-gray-700 last:border-0"
                 onclick="selectStudent(${s.id}, '${escapeJs(s.first_name + ' ' + s.last_name)}', '${escapeJs(s.student_id)}', '${escapeJs(s.email)}')">
                <p class="text-sm font-medium text-gray-900 dark:text-white">${s.first_name} ${s.last_name}</p>
                <p class="text-xs text-gray-400">${s.student_id} · ${s.email}</p>
            </div>`
                ).join('');
            }

            box.classList.remove('hidden');
        }

        function selectStudent(id, name, knumber, email) {
            document.getElementById('selected-student-id').value = id;
            document.getElementById('selected-student-name').textContent = name;
            document.getElementById('selected-student-detail').textContent = `${knumber} · ${email}`;
            document.getElementById('selected-student').classList.remove('hidden');
            document.getElementById('search-results').classList.add('hidden');
            document.getElementById('student-search').value = name;
            document.getElementById('add-btn').disabled = false;
        }

        function escapeJs(str) {
            return str.replace(/'/g, "\\'").replace(/"/g, '\\"');
        }

        // Close search dropdown on outside click
        document.addEventListener('click', e => {
            if (!e.target.closest('#student-search') && !e.target.closest('#search-results')) {
                document.getElementById('search-results').classList.add('hidden');
            }
        });
    </script>
@endsection
