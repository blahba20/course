@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Create New Course</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('courses.store') }}">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="title">Course Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="description">Course Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="max_students">Maximum Number of Students</label>
                            <input type="number" class="form-control" id="max_students" name="max_students" value="{{ old('max_students', 20) }}" min="1" required>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create Course</button>
                            <a href="{{ route('instructor.dashboard') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 