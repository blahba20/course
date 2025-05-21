@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>All Courses</h4>
                    @if(auth()->user()->hasRole('instructor'))
                        <a href="{{ route('courses.create') }}" class="btn btn-success">Create New Course</a>
                    @endif
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($courses->count() > 0)
                        <div class="row">
                            @foreach($courses as $course)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $course->title }}</h5>
                                            <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
                                            <p class="card-text"><small class="text-muted">Instructor: {{ $course->instructor->name }}</small></p>
                                            <p class="card-text"><small class="text-muted">Students: {{ $course->students->count() }} / {{ $course->max_students }}</small></p>
                                        </div>
                                        <div class="card-footer">
                                            <a href="{{ route('courses.show', $course) }}" class="btn btn-primary">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>No courses available at this time.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 