<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Course Portal') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
        }
    </style>
</head>
<body class="d-flex flex-column h-100">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
            <div class="container">
                @auth
                    @if(auth()->user()->isStudent())
                        <a class="navbar-brand" href="{{ route('student.dashboard') }}">Student</a>
                    @else
                        <a class="navbar-brand" href="{{ route('instructor.dashboard') }}">Instructor</a>
                    @endif
                @else
                    <a class="navbar-brand" href="{{ route('home') }}">Course Portal</a>
                @endauth
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                @if(auth()->user()->isStudent())
                                    <a class="nav-link" href="{{ route('student.dashboard') }}">Home</a>
                                @else
                                    <a class="nav-link" href="{{ route('instructor.dashboard') }}">Home</a>
                                @endif
                            </li>
                        @endauth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('courses.index') }}">All Courses</a>
                        </li>
                        @auth
                            @if(auth()->user()->isStudent())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('enrollments.index') }}">My Enrollments</a>
                                </li>
                            @endif
                            @if(auth()->user()->isInstructor())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('instructor.courses') }}">My Courses</a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                    <ul class="navbar-nav">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        @if(auth()->user()->isStudent())
                                            <a class="dropdown-item" href="{{ route('student.dashboard') }}">Dashboard</a>
                                        @else
                                            <a class="dropdown-item" href="{{ route('instructor.dashboard') }}">Dashboard</a>
                                        @endif
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="flex-shrink-0">
        <div class="container">
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

            @yield('content')
        </div>
    </main>

    <footer class="footer mt-auto py-3 bg-dark text-white text-center">
        <div class="container">
            <p class="mb-0">© {{ date('Y') }} Course Portal. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 