<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Doctor Appointment System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #2c3e50;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.8rem 1rem;
            border-radius: 0.25rem;
            margin-bottom: 0.5rem;
        }
        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar .nav-link.active {
            color: white;
            background-color: #3498db;
        }
        .sidebar .nav-link i {
            margin-right: 0.5rem;
        }
        .main-content {
            padding: 2rem;
        }
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
        }
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        .table th {
            font-weight: 600;
            color: #2c3e50;
        }
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
        }
    </style>

    @livewireStyles
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @auth
                <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                    <div class="position-sticky pt-3">
                        <div class="text-center mb-4">
                            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="avatar img-fluid rounded-circle" style="width: 80px; height: 80px;">
                            <h5 class="mt-2 text-white">{{ Auth::user()->name }}</h5>
                            <p class="text-white-50">
                                @if(Auth::user()->isAdmin())
                                    Administrator
                                @elseif(Auth::user()->isDoctor())
                                    Doctor
                                @elseif(Auth::user()->isPatient())
                                    Patient
                                @endif
                            </p>
                        </div>
                        
                        <ul class="nav flex-column">
                            @if(Auth::user()->isAdmin())
                                <li class="nav-item">
                                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.doctors') }}" class="nav-link {{ request()->routeIs('admin.doctors*') ? 'active' : '' }}">
                                        <i class="fas fa-user-md"></i> Doctors
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.patients') }}" class="nav-link {{ request()->routeIs('admin.patients*') ? 'active' : '' }}">
                                        <i class="fas fa-users"></i> Patients
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.appointments') }}" class="nav-link {{ request()->routeIs('admin.appointments*') ? 'active' : '' }}">
                                        <i class="fas fa-calendar-check"></i> Appointments
                                    </a>
                                </li>
                            @elseif(Auth::user()->isDoctor())
                                <li class="nav-item">
                                    <a href="{{ route('doctor.dashboard') }}" class="nav-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('doctor.profile') }}" class="nav-link {{ request()->routeIs('doctor.profile') ? 'active' : '' }}">
                                        <i class="fas fa-user-circle"></i> My Profile
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('doctor.availability') }}" class="nav-link {{ request()->routeIs('doctor.availability*') ? 'active' : '' }}">
                                        <i class="fas fa-clock"></i> Availability
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('doctor.appointments') }}" class="nav-link {{ request()->routeIs('doctor.appointments*') ? 'active' : '' }}">
                                        <i class="fas fa-calendar-check"></i> Appointments
                                    </a>
                                </li>
                            @elseif(Auth::user()->isPatient())
                                <li class="nav-item">
                                    <a href="{{ route('patient.dashboard') }}" class="nav-link {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('patient.profile') }}" class="nav-link {{ request()->routeIs('patient.profile') ? 'active' : '' }}">
                                        <i class="fas fa-user-circle"></i> My Profile
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('patient.doctors') }}" class="nav-link {{ request()->routeIs('patient.doctors*') ? 'active' : '' }}">
                                        <i class="fas fa-user-md"></i> Find Doctors
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('patient.appointments') }}" class="nav-link {{ request()->routeIs('patient.appointments*') ? 'active' : '' }}">
                                        <i class="fas fa-calendar-check"></i> My Appointments
                                    </a>
                                </li>
                            @endif
                        </ul>
                        
                        <hr class="my-3 bg-light">
                        
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}" class="nav-link" 
                                       onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            @endauth

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Top Navigation -->
                <nav class="navbar navbar-expand-lg navbar-light mb-4">
                    <div class="container-fluid">
                        <button class="navbar-toggler d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <a class="navbar-brand" href="#">Doctor Appointment System</a>
                        
                        @auth
                            <div class="dropdown">
                                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="avatar me-2">
                                    <span>{{ Auth::user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
                                    @if(Auth::user()->isAdmin())
                                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                    @elseif(Auth::user()->isDoctor())
                                        <li><a class="dropdown-item" href="{{ route('doctor.profile') }}">My Profile</a></li>
                                    @elseif(Auth::user()->isPatient())
                                        <li><a class="dropdown-item" href="{{ route('patient.profile') }}">My Profile</a></li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a class="dropdown-item" href="{{ route('logout') }}" 
                                               onclick="event.preventDefault(); this.closest('form').submit();">
                                                Logout
                                            </a>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endauth
                    </div>
                </nav>
                
                <!-- Page Content -->
                <div class="container-fluid">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @livewireScripts
    
    <!-- Custom Scripts -->
    <script>
        // Add any custom JavaScript here
    </script>
    
    @stack('scripts')
</body>
</html>
