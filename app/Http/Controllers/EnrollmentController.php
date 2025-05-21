<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // Authentication will be handled through route middleware
    }

    /**
     * Display a listing of the student's enrollments.
     */
    public function index()
    {
        $enrollments = Enrollment::where('user_id', Auth::id())->with('course')->get();
        return view('enrollments.index', compact('enrollments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created enrollment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $course = Course::findOrFail($request->course_id);

        // Check if course has available slots
        if (!$course->hasAvailableSlots()) {
            return back()->with('error', 'This course is full.');
        }

        // Check if student is already enrolled
        $existingEnrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            return back()->with('error', 'You are already enrolled in this course.');
        }

        // Create enrollment
        Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'status' => 'active',
        ]);

        return redirect()->route('enrollments.index')
            ->with('success', 'Successfully enrolled in the course.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the enrollment status.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        /** @var User $user */
        $user = Auth::user();
        $isInstructor = $user->isInstructor();
        $isStudent = $enrollment->user_id === $user->id;
        
        // Check if the user has permission to update this enrollment
        // (Either the student who enrolled or the course instructor)
        if (!$isStudent && !($isInstructor && $enrollment->course->instructor_id === $user->id)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:active,completed,dropped',
        ]);

        $enrollment->update([
            'status' => $request->status,
        ]);

        // Redirect based on who updated the status
        if ($isInstructor) {
            return redirect()->route('courses.students', $enrollment->course_id)
                ->with('success', 'Student enrollment status updated.');
        }
        
        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment status updated.');
    }

    /**
     * Remove the enrollment.
     */
    public function destroy(Enrollment $enrollment)
    {
        /** @var User $user */
        $user = Auth::user();
        $isInstructor = $user->isInstructor();
        $isStudent = $enrollment->user_id === $user->id;
        
        // Check if the user has permission to delete this enrollment
        // (Either the student who enrolled or the course instructor)
        if (!$isStudent && !($isInstructor && $enrollment->course->instructor_id === $user->id)) {
            abort(403, 'Unauthorized action.');
        }

        $enrollment->delete();
        
        // Redirect based on who deleted the enrollment
        if ($isInstructor) {
            return redirect()->route('courses.students', $enrollment->course_id)
                ->with('success', 'Student removed from the course.');
        }

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment cancelled successfully.');
    }

    /**
     * Display students enrolled in a specific course (for instructors).
     */
    public function students(Course $course)
    {
        // Check if the user is the instructor of this course
        if ($course->instructor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $enrollments = $course->enrollments()->with('student')->get();
        return view('enrollments.students', compact('course', 'enrollments'));
    }

    /**
     * Enroll the current user in a course.
     */
    public function enroll(Course $course)
    {
        // Check if course is active
        if (!$course->active) {
            return back()->with('error', 'This course is not currently active.');
        }

        // Check if course has available slots
        if ($course->students->count() >= $course->max_students) {
            return back()->with('error', 'This course is full.');
        }

        // Check if student is already enrolled
        if ($course->students->contains(Auth::user())) {
            return back()->with('error', 'You are already enrolled in this course.');
        }

        // Create enrollment
        Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'status' => 'active',
        ]);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Successfully enrolled in the course.');
    }

    /**
     * Unenroll the current user from a course.
     */
    public function unenroll(Course $course)
    {
        // Check if student is enrolled
        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'You are not enrolled in this course.');
        }

        // Delete enrollment
        $enrollment->delete();

        return redirect()->route('courses.show', $course)
            ->with('success', 'Successfully unenrolled from the course.');
    }
}
