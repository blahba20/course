@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body text-center">
                    <h1 class="display-4 mb-4">Welcome to Course Portal</h1>
                    <p class="lead">A platform for students to discover and enroll in courses, and for instructors to share their knowledge.</p>
                    
                    <div class="mt-5">
                        @guest
                            <div class="row justify-content-center">
                                <div class="col-md-5">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h3>Students</h3>
                                            <p>Browse and enroll in a variety of courses taught by expert instructors.</p>
                                            <div class="d-grid gap-2">
                                                <a href="{{ route('login') }}?role=student" class="btn btn-primary mb-2">Login as Student</a>
                                                <a href="{{ route('register') }}?role=student" class="btn btn-outline-primary">Register as Student</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3>Instructors</h3>
                                            <p>Create and manage courses, and connect with eager students.</p>
                                            <div class="d-grid gap-2">
                                                <a href="{{ route('login') }}?role=instructor" class="btn btn-success mb-2">Login as Instructor</a>
                                                <a href="{{ route('register') }}?role=instructor" class="btn btn-outline-success">Register as Instructor</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mt-4">
                                @if(auth()->user()->isStudent())
                                    <a href="{{ route('student.dashboard') }}" class="btn btn-lg btn-primary">Go to Student Dashboard</a>
                                @else
                                    <a href="{{ route('instructor.dashboard') }}" class="btn btn-lg btn-success">Go to Instructor Dashboard</a>
                                @endif
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
