@extends('layouts.app')

@section('title', 'Students — Admin')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Students</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $students->total() }} registered students</p>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="text-sm text-blue-600 dark:text-blue-400 hover:underline">← Back to Dashboard</a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Name</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Email</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Major</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Student ID</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($students as $student)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($student->profile_photo)
                                        <img src="{{ asset('storage/' . $student->profile_photo) }}"
                                             alt="" class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-semibold">
                                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $student->fullName() }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $student->email }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $student->major ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $student->student_id ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $student->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500">No students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($students->hasPages())
                <div class="px-4 py-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
