@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<div class="hero">
    <div class="container-lg">
        <h1>Your Gateway to Success</h1>
        <p>Discover scholarships, internships, training programs, and job opportunities tailored for TUP-Taguig students</p>
        
        <div class="row justify-content-center g-3">
            <div class="col-md-6">
                <form action="{{ route('opportunities.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-lg" 
                           placeholder="Search opportunities..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-warning btn-lg">
                        <i class="fas fa-search"></i> Search
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container-lg my-5">
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-star fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Featured Opportunities</h5>
                    <p class="card-text">Discover hand-picked opportunities curated for you</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-bell fa-3x text-info mb-3"></i>
                    <h5 class="card-title">Smart Notifications</h5>
                    <p class="card-text">Get deadline reminders and application updates</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-bookmark fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Save & Track</h5>
                    <p class="card-text">Save opportunities and track your applications</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-chart-line fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">Success Dashboard</h5>
                    <p class="card-text">Monitor your progress and application status</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Opportunity Types -->
<div class="container-lg my-5">
    <h2 class="mb-4">Explore Opportunities by Type</h2>
    
    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('opportunities.index', ['type' => 'scholarship']) }}" class="text-decoration-none">
                <div class="card text-center h-100 border-0" style="border-top: 4px solid #1971c2 !important;">
                    <div class="card-body">
                        <i class="fas fa-graduation-cap fa-3x mb-3" style="color: #1971c2;"></i>
                        <h5 class="card-title">Scholarships</h5>
                        <p class="card-text text-muted">Financial aid and tuition support</p>
                        <p class="text-primary fw-bold">
                            {{ \App\Models\Opportunity::where('type', 'scholarship')->where('status', 'active')->count() }}
                            Available
                        </p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('opportunities.index', ['type' => 'internship']) }}" class="text-decoration-none">
                <div class="card text-center h-100 border-0" style="border-top: 4px solid #e65100 !important;">
                    <div class="card-body">
                        <i class="fas fa-briefcase fa-3x mb-3" style="color: #e65100;"></i>
                        <h5 class="card-title">Internships</h5>
                        <p class="card-text text-muted">Gain practical experience</p>
                        <p class="text-primary fw-bold">
                            {{ \App\Models\Opportunity::where('type', 'internship')->where('status', 'active')->count() }}
                            Available
                        </p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('opportunities.index', ['type' => 'training']) }}" class="text-decoration-none">
                <div class="card text-center h-100 border-0" style="border-top: 4px solid #0369a1 !important;">
                    <div class="card-body">
                        <i class="fas fa-chalkboard fa-3x mb-3" style="color: #0369a1;"></i>
                        <h5 class="card-title">Training</h5>
                        <p class="card-text text-muted">Professional development programs</p>
                        <p class="text-primary fw-bold">
                            {{ \App\Models\Opportunity::where('type', 'training')->where('status', 'active')->count() }}
                            Available
                        </p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('opportunities.index', ['type' => 'job']) }}" class="text-decoration-none">
                <div class="card text-center h-100 border-0" style="border-top: 4px solid #166534 !important;">
                    <div class="card-body">
                        <i class="fas fa-rocket fa-3x mb-3" style="color: #166534;"></i>
                        <h5 class="card-title">Jobs</h5>
                        <p class="card-text text-muted">Full-time and part-time positions</p>
                        <p class="text-primary fw-bold">
                            {{ \App\Models\Opportunity::where('type', 'job')->where('status', 'active')->count() }}
                            Available
                        </p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="container-lg my-5">
    <div class="card" style="background: linear-gradient(135deg, #003E90 0%, #004ab8 100%); color: white; border: none;">
        <div class="card-body p-5 text-center">
            @auth
                @if(auth()->user()->isStudent())
                    <h3>Ready to Apply?</h3>
                    <p class="mb-4">Browse our collection of opportunities and start your journey to success</p>
                    <a href="{{ route('opportunities.index') }}" class="btn btn-warning btn-lg">
                        <i class="fas fa-arrow-right"></i> Explore Now
                    </a>
                @elseif(auth()->user()->isProvider())
                    <h3>Post Your Opportunities</h3>
                    <p class="mb-4">Find talented TUP-Taguig students for your organization</p>
                    <a href="{{ route('provider.opportunities.create') }}" class="btn btn-warning btn-lg">
                        <i class="fas fa-plus"></i> Create Opportunity
                    </a>
                @endif
            @else
                <h3>Join Our Community</h3>
                <p class="mb-4">Sign up today to unlock exclusive opportunities and track your applications</p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-warning btn-lg">
                        <i class="fas fa-user-plus"></i> Register
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="container-lg my-5">
    <h2 class="text-center mb-5">Our Impact</h2>
    
    <div class="row text-center g-4">
        <div class="col-md-3">
            <div>
                <h3 class="text-primary fw-bold">{{ \App\Models\User::where('role', 'student')->count() }}</h3>
                <p class="text-muted">Active Students</p>
            </div>
        </div>
        <div class="col-md-3">
            <div>
                <h3 class="text-primary fw-bold">{{ \App\Models\User::where('role', 'provider')->count() }}</h3>
                <p class="text-muted">Partner Organizations</p>
            </div>
        </div>
        <div class="col-md-3">
            <div>
                <h3 class="text-primary fw-bold">{{ \App\Models\Opportunity::count() }}</h3>
                <p class="text-muted">Total Opportunities</p>
            </div>
        </div>
        <div class="col-md-3">
            <div>
                <h3 class="text-primary fw-bold">{{ \App\Models\Application::where('status', 'accepted')->count() }}</h3>
                <p class="text-muted">Successful Placements</p>
            </div>
        </div>
    </div>
</div>
@endsection
