@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Student Dashboard</h4>
                </div>
                <div class="card-body">
                    <h5>Welcome, {{ auth()->user()->name }}!</h5>
                    <p>You are logged in as a student.</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Quick Actions</div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <a href="{{ route('courses.index') }}" class="btn btn-primary">Browse Courses</a>
                                        </li>
                                        <li class="list-group-item">
                                            <a href="{{ route('enrollments.index') }}" class="btn btn-info">View My Enrollments</a>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between">
                                                <span>Export Enrollments:</span>
                                                <div>
                                                    <a href="{{ route('export.student.enrollments.json') }}" class="btn btn-sm btn-secondary">JSON</a>
                                                    <a href="{{ route('export.student.enrollments.xml') }}" class="btn btn-sm btn-secondary">XML</a>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">My Stats</div>
                                <div class="card-body">
                                    <p><strong>Total Enrollments:</strong> {{ auth()->user()->enrollments->count() }}</p>
                                    <p><strong>Active Courses:</strong> {{ auth()->user()->enrollments->where('status', 'active')->count() }}</p>
                                    <p><strong>Completed Courses:</strong> {{ auth()->user()->enrollments->where('status', 'completed')->count() }}</p>
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