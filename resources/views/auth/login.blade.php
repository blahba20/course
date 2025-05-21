@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                @if(isset($role) && $role == 'student')
                    Student Login
                @elseif(isset($role) && $role == 'instructor')
                    Instructor Login
                @else
                    Login
                @endif
            </div>
            <div class="card-body">
                @error('role')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @enderror
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    @if(isset($role) && in_array($role, ['student', 'instructor']))
                        <input type="hidden" name="role" value="{{ $role }}">
                    @endif
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        @if(isset($role) && $role == 'student')
                            Login as Student
                        @elseif(isset($role) && $role == 'instructor')
                            Login as Instructor
                        @else
                            Login
                        @endif
                    </button>
                </form>
                <div class="mt-3">
                    <p>Don't have an account? 
                        @if(isset($role) && $role == 'student')
                            <a href="{{ route('register') }}?role=student">Register as Student</a>
                        @elseif(isset($role) && $role == 'instructor')
                            <a href="{{ route('register') }}?role=instructor">Register as Instructor</a>
                        @else
                            <a href="{{ route('register') }}">Register here</a>
                        @endif
                    </p>
                    <p><a href="{{ route('home') }}">Back to home</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 