@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Students in Course: {{ $course->title }}</h4>
                    <a href="{{ route('courses.show', $course) }}" class="btn btn-secondary">Back to Course</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h5>Course Details</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Total Enrolled</th>
                                    <td>{{ $enrollments->count() }} / {{ $course->max_students }}</td>
                                </tr>
                                <tr>
                                    <th>Course Status</th>
                                    <td>
                                        @if($course->active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <h5>Enrolled Students</h5>
                    @if($enrollments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Enrollment Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrollments as $enrollment)
                                        <tr>
                                            <td>{{ $enrollment->student->name }}</td>
                                            <td>{{ $enrollment->student->email }}</td>
                                            <td>{{ $enrollment->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $enrollment->status === 'active' ? 'success' : ($enrollment->status === 'completed' ? 'primary' : 'warning') }}">
                                                    {{ ucfirst($enrollment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <form action="{{ route('enrollments.destroy', $enrollment) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this student from the course?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                                    </form>
                                                    
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                            Update Status
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <form action="{{ route('enrollments.update', $enrollment) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="status" value="active">
                                                                    <button type="submit" class="dropdown-item">Active</button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('enrollments.update', $enrollment) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="status" value="completed">
                                                                    <button type="submit" class="dropdown-item">Completed</button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('enrollments.update', $enrollment) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="status" value="dropped">
                                                                    <button type="submit" class="dropdown-item">Dropped</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No students are currently enrolled in this course.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 