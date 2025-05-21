@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>My Courses</h4>
                    <div>
                        <div class="btn-group me-2">
                            <a href="{{ route('export.instructor.courses.json') }}" class="btn btn-sm btn-secondary">Export JSON</a>
                            <a href="{{ route('export.instructor.courses.xml') }}" class="btn btn-sm btn-secondary">Export XML</a>
                        </div>
                        <a href="{{ route('courses.create') }}" class="btn btn-primary">Create New Course</a>
                    </div>
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
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    Students: {{ $course->students->count() }}/{{ $course->max_students }}
                                                </small>
                                            </p>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    Status: {{ $course->active ? 'Active' : 'Inactive' }}
                                                </small>
                                            </p>
                                        </div>
                                        <div class="card-footer d-flex justify-content-between">
                                            <div>
                                                <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-info">View</a>
                                                <a href="{{ route('courses.students', $course) }}" class="btn btn-sm btn-primary">Students</a>
                                            </div>
                                            <div>
                                                <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>You haven't created any courses yet.</p>
                        <a href="{{ route('courses.create') }}" class="btn btn-primary">Create Your First Course</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 