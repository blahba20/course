<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Constructor to apply middleware
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the student's enrollments.
     */
    public function index()
    {
        $enrollments = Auth::user()->enrollments()->with('course')->get();
        return response()->json(['enrollments' => $enrollments]);
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
            return response()->json([
                'message' => 'This course is full.'
            ], 400);
        }

        // Check if student is already enrolled
        $existingEnrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            return response()->json([
                'message' => 'You are already enrolled in this course.'
            ], 400);
        }

        // Create enrollment
        $enrollment = Auth::user()->enrollments()->create([
            'course_id' => $course->id,
            'status' => 'active',
        ]);

        return response()->json([
            'message' => 'Successfully enrolled in the course.',
            'enrollment' => $enrollment
        ], 201);
    }

    /**
     * Display the specified enrollment.
     */
    public function show(Enrollment $enrollment)
    {
        // Check if the enrollment belongs to the current user
        if ($enrollment->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized action'
            ], 403);
        }

        return response()->json(['enrollment' => $enrollment->load('course')]);
    }

    /**
     * Update the enrollment status.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        // Check if the enrollment belongs to the current user
        if ($enrollment->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized action'
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:active,completed,dropped',
        ]);

        $enrollment->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Enrollment status updated.',
            'enrollment' => $enrollment
        ]);
    }

    /**
     * Remove the enrollment.
     */
    public function destroy(Enrollment $enrollment)
    {
        // Check if the enrollment belongs to the current user
        if ($enrollment->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized action'
            ], 403);
        }

        $enrollment->delete();

        return response()->json([
            'message' => 'Enrollment cancelled successfully.'
        ]);
    }

    /**
     * Display students enrolled in a specific course (for instructors).
     */
    public function courseStudents(Course $course)
    {
        // Check if the user is the instructor of this course
        if ($course->instructor_id !== Auth::id() || !Auth::user()->isInstructor()) {
            return response()->json([
                'message' => 'Unauthorized action'
            ], 403);
        }

        $enrollments = $course->enrollments()->with('student')->get();
        
        return response()->json([
            'course' => $course,
            'enrollments' => $enrollments
        ]);
    }
}
