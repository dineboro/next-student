<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassSectionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:instructor']);
    }

    public function index()
    {

        $sections = ClassSection::where('instructor_id', Auth::id())
            ->withCount('students')
            ->withCount(['helpRequests as pending_requests_count' => function ($q) {
                $q->where('status', 'pending');
            }])
            ->latest()
            ->get();

        return view('instructor.sections.index', compact('sections'));
    }

    public function create()
    {
        return view('instructor.sections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:50',
            'semester'    => 'required|in:Fall,Spring,Summer',
            'year'        => 'required|integer|min:2020|max:2099',
        ]);

        $section = ClassSection::create([
            ...$validated,
            'instructor_id' => Auth::id(),
            'is_active'     => true,
        ]);

        return redirect()->route('instructor.sections.show', $section)
            ->with('success', "Section \"{$section->course_name}\" created! Now add students to the roster.");
    }

    public function show(ClassSection $section)
    {
        $this->authorizeSection($section);

        $section->load(['students' => function ($q) {
            $q->orderBy('first_name');
        }]);

        return view('instructor.sections.show', compact('section'));
    }

    public function edit(ClassSection $section)
    {
        $this->authorizeSection($section);
        return view('instructor.sections.create', compact('section'));
    }

    public function update(Request $request, ClassSection $section)
    {
        $this->authorizeSection($section);

        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:50',
            'semester'    => 'required|in:Fall,Spring,Summer',
            'year'        => 'required|integer|min:2020|max:2099',
            'is_active'   => 'boolean',
        ]);

        $section->update($validated);

        return redirect()->route('instructor.sections.show', $section)
            ->with('success', 'Section updated successfully.');
    }

    public function destroy(ClassSection $section)
    {
        $this->authorizeSection($section);
        $section->delete();

        return redirect()->route('instructor.sections.index')
            ->with('success', 'Section deleted.');
    }

    // -------------------------------------------------------------------------
    // Roster management
    // -------------------------------------------------------------------------

    public function searchStudent(Request $request)
    {
        $request->validate(['query' => 'required|string|min:2']);

        $q = $request->input('query');

        $students = User::where('role', 'student')
            ->where('approval_status', 'approved')
            ->where(function ($query) use ($q) {
                $query->where('student_id', 'like', "%{$q}%")
                    ->orWhere('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'email', 'student_id', 'major']);

        return response()->json($students);
    }

    public function addStudent(Request $request, ClassSection $section)
    {
        $this->authorizeSection($section);

        $request->validate(['student_id' => 'required|exists:users,id']);

        $student = User::findOrFail($request->student_id);

        if ($student->role !== 'student') {
            return back()->withErrors(['student_id' => 'Only students can be added to a section.']);
        }

        if ($section->students()->whereKey($student->id)->exists()) {
            return back()->with('info', "{$student->fullName()} is already in this section.");
        }

        $section->students()->attach($student->id);

        return back()->with('success', "{$student->fullName()} added to the section.");
    }

    public function removeStudent(ClassSection $section, User $student)
    {
        $this->authorizeSection($section);

        $section->students()->detach($student->id);

        return back()->with('success', "{$student->fullName()} removed from the section.");
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function authorizeSection(ClassSection $section): void
    {
        abort_if($section->instructor_id !== Auth::id(), 403, 'Unauthorized.');
    }
}
