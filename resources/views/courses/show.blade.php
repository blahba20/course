@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ $course->title }}</h4>
                    <div>
                        @if(auth()->id() === $course->instructor_id)
                            <a href="{{ route('courses.edit', $course) }}" class="btn btn-warning">Edit Course</a>
                            <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        @endif
                        <a href="{{ route('courses.index') }}" class="btn btn-secondary">Back to Courses</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h5>Description:</h5>
                    <p>{{ $course->description }}</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Course Information:</h5>
                            <ul class="list-group">
                                <li class="list-group-item"><strong>Instructor:</strong> {{ $course->instructor->name }}</li>
                                <li class="list-group-item"><strong>Maximum Students:</strong> {{ $course->max_students }}</li>
                                <li class="list-group-item"><strong>Current Enrollment:</strong> {{ $course->students->count() }} student(s)</li>
                                <li class="list-group-item"><strong>Status:</strong> {{ $course->active ? 'Active' : 'Inactive' }}</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Actions:</h5>
                            <ul class="list-group">
                                @if(auth()->id() === $course->instructor_id)
                                    <li class="list-group-item">
                                        <a href="{{ route('courses.students', $course) }}" class="btn btn-primary w-100">Manage Students</a>
                                    </li>
                                @elseif(auth()->user()->hasRole('student'))
                                    @if($course->students->contains(auth()->user()))
                                        <li class="list-group-item">
                                            <form action="{{ route('courses.unenroll', $course) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger w-100">Unenroll from Course</button>
                                            </form>
                                        </li>
                                    @elseif($course->students->count() < $course->max_students && $course->active)
                                        <li class="list-group-item">
                                            <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success w-100">Enroll in Course</button>
                                            </form>
                                        </li>
                                    @endif
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 