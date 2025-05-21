<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Constructor to apply middleware
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('role:instructor')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the courses.
     */
    public function index()
    {
        $courses = Course::where('active', true)->get();
        return response()->json(['courses' => $courses]);
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'max_students' => 'required|integer|min:1',
        ]);

        $course = Auth::user()->courses()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'max_students' => $validated['max_students'],
        ]);

        return response()->json([
            'message' => 'Course created successfully',
            'course' => $course
        ], 201);
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        return response()->json(['course' => $course]);
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        // Check if the user is the instructor of this course
        if ($course->instructor_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized action'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'max_students' => 'sometimes|required|integer|min:1',
            'active' => 'sometimes|boolean',
        ]);

        $course->update($validated);

        return response()->json([
            'message' => 'Course updated successfully',
            'course' => $course
        ]);
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        // Check if the user is the instructor of this course
        if ($course->instructor_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized action'
            ], 403);
        }

        $course->delete();

        return response()->json([
            'message' => 'Course deleted successfully'
        ]);
    }
}
