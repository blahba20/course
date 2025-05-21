@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Instructor Dashboard</h4>
                </div>
                <div class="card-body">
                    <h5>Welcome, {{ auth()->user()->name }}!</h5>
                    <p>You are logged in as an instructor.</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Quick Actions</div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <a href="{{ route('courses.create') }}" class="btn btn-success">Create New Course</a>
                                        </li>
                                        <li class="list-group-item">
                                            <a href="{{ route('courses.index') }}" class="btn btn-primary">View All Courses</a>
                                        </li>
                                        <li class="list-group-item">
                                            <a href="{{ route('instructor.courses') }}" class="btn btn-info">Manage My Courses</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div id="my-courses" class="card">
                                <div class="card-header">My Courses</div>
                                <div class="card-body">
                                    @if(auth()->user()->courses->count() > 0)
                                        <ul class="list-group">
                                            @foreach(auth()->user()->courses as $course)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>{{ $course->title }}</span>
                                                    <div>
                                                        <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-info">View</a>
                                                        <a href="{{ route('courses.students', $course) }}" class="btn btn-sm btn-primary">Students</a>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>You haven't created any courses yet.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 