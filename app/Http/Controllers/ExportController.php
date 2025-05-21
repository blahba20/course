<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Export student enrollments in JSON format
     */
    public function studentEnrollmentsJson()
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->isStudent()) {
            abort(403, 'Unauthorized action.');
        }

        $enrollments = $user->enrollments()->with(['course', 'course.instructor:id,name'])->get();
        
        $data = $enrollments->map(function ($enrollment) {
            return [
                'enrollment_id' => $enrollment->id,
                'status' => $enrollment->status,
                'enrolled_at' => $enrollment->created_at,
                'course' => [
                    'id' => $enrollment->course->id,
                    'title' => $enrollment->course->title,
                    'description' => $enrollment->course->description,
                    'instructor_name' => $enrollment->course->instructor->name,
                ]
            ];
        });
        
        $json = json_encode($data, JSON_PRETTY_PRINT);
        $filename = 'enrollments_' . date('Y-m-d') . '.json';
        
        return Response::make($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    /**
     * Export student enrollments in XML format
     */
    public function studentEnrollmentsXml()
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->isStudent()) {
            abort(403, 'Unauthorized action.');
        }

        $enrollments = $user->enrollments()->with(['course', 'course.instructor:id,name'])->get();
        
        $xml = new \SimpleXMLElement('<enrollments></enrollments>');
        
        foreach ($enrollments as $enrollment) {
            $item = $xml->addChild('enrollment');
            $item->addChild('enrollment_id', $enrollment->id);
            $item->addChild('status', $enrollment->status);
            $item->addChild('enrolled_at', $enrollment->created_at);
            
            $course = $item->addChild('course');
            $course->addChild('id', $enrollment->course->id);
            $course->addChild('title', $enrollment->course->title);
            $course->addChild('description', $enrollment->course->description);
            $course->addChild('instructor_name', $enrollment->course->instructor->name);
        }
        
        $xmlContent = $xml->asXML();
        $filename = 'enrollments_' . date('Y-m-d') . '.xml';
        
        return Response::make($xmlContent, 200, [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    /**
     * Export instructor courses in JSON format
     */
    public function instructorCoursesJson(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->isInstructor()) {
            abort(403, 'Unauthorized action.');
        }

        $courses = $user->courses()->with('students:id,name,email')->get();
        
        $data = $courses->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'max_students' => $course->max_students,
                'active' => $course->active,
                'created_at' => $course->created_at,
                'students' => $course->students->map(function ($student) {
                    $enrollment = Enrollment::where('course_id', $student->pivot->course_id)
                        ->where('user_id', $student->id)
                        ->first();
                        
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'email' => $student->email,
                        'status' => $student->pivot->status,
                        'enrolled_at' => $enrollment ? $enrollment->created_at : null,
                    ];
                })
            ];
        });
        
        $json = json_encode($data, JSON_PRETTY_PRINT);
        $filename = 'instructor_courses_' . date('Y-m-d') . '.json';
        
        return Response::make($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    /**
     * Export instructor courses in XML format
     */
    public function instructorCoursesXml()
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->isInstructor()) {
            abort(403, 'Unauthorized action.');
        }

        $courses = $user->courses()->with('students:id,name,email')->get();
        
        $xml = new \SimpleXMLElement('<courses></courses>');
        
        foreach ($courses as $course) {
            $item = $xml->addChild('course');
            $item->addChild('id', $course->id);
            $item->addChild('title', $course->title);
            $item->addChild('description', $course->description);
            $item->addChild('max_students', $course->max_students);
            $item->addChild('active', $course->active ? 'true' : 'false');
            $item->addChild('created_at', $course->created_at);
            
            $students = $item->addChild('students');
            foreach ($course->students as $student) {
                $studentItem = $students->addChild('student');
                $studentItem->addChild('id', $student->id);
                $studentItem->addChild('name', $student->name);
                $studentItem->addChild('email', $student->email);
                $studentItem->addChild('status', $student->pivot->status);
                
                $enrollment = Enrollment::where('course_id', $student->pivot->course_id)
                    ->where('user_id', $student->id)
                    ->first();
                
                $studentItem->addChild('enrolled_at', $enrollment ? $enrollment->created_at : '');
            }
        }
        
        $xmlContent = $xml->asXML();
        $filename = 'instructor_courses_' . date('Y-m-d') . '.xml';
        
        return Response::make($xmlContent, 200, [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }
} 