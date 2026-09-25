<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - OpportunityHub</title>
    
    <!-- TUP-Taguig Branding -->
    <meta name="description" content="OpportunityHub - Your gateway to scholarships, internships, and job opportunities at TUP-Taguig">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --tup-gold: #FDB913;
            --tup-blue: #003E90;
            --tup-dark: #001a3d;
            --accent: #FF6B6B;
            --success: #51CF66;
            --warning: #FFD93D;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f8f9fa;
            color: #333;
        }

        /* Navigation */
        .navbar {
            background: linear-gradient(135deg, var(--tup-blue) 0%, #004ab8 100%);
            box-shadow: 0 2px 10px rgba(0, 30, 144, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand::before {
            content: "🎓";
            font-size: 1.8rem;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            margin-right: 1rem;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: var(--tup-gold) !important;
        }

        .navbar-nav .nav-link.active {
            color: var(--tup-gold) !important;
            border-bottom: 2px solid var(--tup-gold);
        }

        .user-menu .dropdown-toggle::after {
            border-top: 0.3em solid var(--tup-gold);
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--tup-blue);
            border-color: var(--tup-blue);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--tup-dark);
            border-color: var(--tup-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 30, 144, 0.2);
        }

        .btn-outline-primary {
            color: var(--tup-blue);
            border-color: var(--tup-blue);
        }

        .btn-outline-primary:hover {
            background-color: var(--tup-blue);
            border-color: var(--tup-blue);
        }

        /* Cards */
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            transform: translateY(-4px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--tup-blue) 0%, #004ab8 100%);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1.5rem;
            border-radius: 10px 10px 0 0;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--tup-blue) 0%, #004ab8 100%);
            color: white;
            padding: 5rem 2rem;
            text-align: center;
            margin-bottom: 3rem;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        /* Badges */
        .badge-opportunity-type {
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
        }

        .badge-scholarship {
            background-color: #e7f5ff;
            color: #1971c2;
        }

        .badge-internship {
            background-color: #fff3e0;
            color: #e65100;
        }

        .badge-training {
            background-color: #f0f9ff;
            color: #0369a1;
        }

        .badge-job {
            background-color: #f0fdf4;
            color: #166534;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 10px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        /* Footer */
        footer {
            background-color: var(--tup-blue);
            color: white;
            padding: 3rem 0;
            margin-top: 5rem;
        }

        footer a {
            color: var(--tup-gold);
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .navbar-nav {
                padding-top: 1rem;
            }

            .navbar-nav .nav-link {
                padding: 0.5rem 0;
            }
        }

        /* Loading spinner */
        .spinner-center {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 300px;
        }

        /* Notification badge */
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--accent);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }
    </style>

    @stack('css')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-lg">
            <a class="navbar-brand" href="{{ route('home') }}">OpportunityHub</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('home')) active @endif" href="{{ route('home') }}">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('opportunities.*')) active @endif" href="{{ route('opportunities.index') }}">
                            <i class="fas fa-briefcase"></i> Opportunities
                        </a>
                    </li>
                    
                    @auth
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                        </li>
                        
                        @if(auth()->user()->isStudent())
                            <li class="nav-item">
                                <a class="nav-link @if(request()->routeIs('applications.*')) active @endif" href="{{ route('applications.index') }}">
                                    <i class="fas fa-file-check"></i> My Applications
                                </a>
                            </li>
                        @elseif(auth()->user()->isProvider())
                            <li class="nav-item">
                                <a class="nav-link @if(request()->routeIs('provider.*')) active @endif" href="{{ route('provider.opportunities.index') }}">
                                    <i class="fas fa-tasks"></i> My Opportunities
                                </a>
                            </li>
                        @endif

                        <li class="nav-item user-menu">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                @php
                                    $unreadCount = auth()->user()->getUnreadNotificationCount();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="notification-badge">{{ min($unreadCount, 9) }}</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @forelse(auth()->user()->unreadNotifications()->limit(5)->get() as $notif)
                                    <li>
                                        <a class="dropdown-item small" href="#" onclick="markNotificationAsRead({{ $notif->id }})">
                                            <i class="fas fa-{{ $notif->getIconClass() }}"></i> {{ $notif->title }}
                                        </a>
                                    </li>
                                @empty
                                    <li><span class="dropdown-item small text-muted">No new notifications</span></li>
                                @endforelse
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item small" href="{{ route('applications.index') }}">View all</a></li>
                            </ul>
                        </li>
                        
                        <li class="nav-item user-menu">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-cog"></i> Edit Profile
                                </a></li>
                                @if(auth()->user()->isStudent())
                                    <li><a class="dropdown-item" href="{{ route('profile.student.edit') }}">
                                        <i class="fas fa-graduation-cap"></i> Student Info
                                    </a></li>
                                @elseif(auth()->user()->isProvider())
                                    <li><a class="dropdown-item" href="{{ route('profile.provider.edit') }}">
                                        <i class="fas fa-building"></i> Company Info
                                    </a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-warning text-white ms-2" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-lg py-4">
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="mt-5">
        <div class="container-lg">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>OpportunityHub</h5>
                    <p>Empowering TUP-Taguig students to find their ideal opportunities in scholarships, internships, training, and jobs.</p>
                    <div class="social-links">
                        <a href="#" class="me-3"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('opportunities.index') }}">Browse Opportunities</a></li>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="#about">About Us</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6>For Providers</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('register') }}">Post Opportunity</a></li>
                        <li><a href="#">Partner With Us</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Contact Us</h6>
                    <p>
                        <i class="fas fa-envelope"></i> support@opportunityhub.ph<br>
                        <i class="fas fa-phone"></i> (02) 8641-2580<br>
                        <i class="fas fa-map-marker-alt"></i> Technological University of the Philippines - Taguig
                    </p>
                </div>
            </div>
            <hr class="bg-warning">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} OpportunityHub. All rights reserved. TUP-Taguig © 2024</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function markNotificationAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                location.reload();
            });
        }

        // Auto-dismiss alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            if (!alert.classList.contains('alert-permanent')) {
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 5000);
            }
        });
    </script>

    @stack('js')
</body>
</html>
