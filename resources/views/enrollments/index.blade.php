@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4>My Enrollments</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($enrollments->count() > 0)
                        <div class="row">
                            @foreach($enrollments as $enrollment)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $enrollment->course->title }}</h5>
                                            <p class="card-text">{{ Str::limit($enrollment->course->description, 100) }}</p>
                                            <p class="card-text"><small class="text-muted">Instructor: {{ $enrollment->course->instructor->name }}</small></p>
                                            <p class="card-text"><small class="text-muted">Status: {{ ucfirst($enrollment->status) }}</small></p>
                                        </div>
                                        <div class="card-footer d-flex justify-content-between">
                                            <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-primary">View Course</a>
                                            <form action="{{ route('enrollments.destroy', $enrollment) }}" method="POST" onsubmit="return confirm('Are you sure you want to unenroll from this course?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Unenroll</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>You are not enrolled in any courses.</p>
                        <a href="{{ route('courses.index') }}" class="btn btn-primary">Browse Available Courses</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 