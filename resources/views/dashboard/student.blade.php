@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1">
            <i class="fas fa-wave"></i> Welcome, {{ auth()->user()->name }}!
        </h2>
        <p class="text-muted">Track your applications and discover new opportunities</p>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="{{ route('opportunities.index') }}" class="btn btn-primary">
            <i class="fas fa-briefcase"></i> Browse Opportunities
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-file-check fa-2x text-primary mb-2"></i>
                <h5>{{ $stats['applications'] }}</h5>
                <p class="text-muted small mb-0">Total Applications</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-6">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-bookmark fa-2x text-info mb-2"></i>
                <h5>{{ $stats['saved'] }}</h5>
                <p class="text-muted small mb-0">Saved Opportunities</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-6">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-star fa-2x text-warning mb-2"></i>
                <h5>{{ $stats['shortlisted'] }}</h5>
                <p class="text-muted small mb-0">Shortlisted</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-6">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                <h5>{{ $stats['accepted'] }}</h5>
                <p class="text-muted small mb-0">Accepted</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Applications -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Applications</h5>
                    <a href="{{ route('applications.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
            </div>
            
            <div class="card-body p-0">
                @forelse($recentApplications as $application)
                    <div class="border-bottom p-3" style="border-bottom: 1px solid #dee2e6;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <a href="{{ route('applications.show', $application) }}" class="text-decoration-none">
                                    <h6 class="mb-1">{{ $application->opportunity->title }}</h6>
                                </a>
                                <p class="text-muted small mb-2">{{ $application->opportunity->provider->company_name }}</p>
                                <div class="d-flex gap-2">
                                    <span class="badge badge-opportunity-type" style="background-color: #e7f5ff; color: #1971c2; font-size: 0.75rem;">
                                        {{ ucfirst($application->opportunity->type) }}
                                    </span>
                                    @php
                                        $statusColors = [
                                            'submitted' => 'info',
                                            'under_review' => 'warning',
                                            'shortlisted' => 'success',
                                            'accepted' => 'success',
                                            'rejected' => 'danger',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$application->status] ?? 'secondary' }}" style="font-size: 0.75rem;">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </div>
                            </div>
                            <small class="text-muted">{{ $application->submitted_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @empty
                    <div class="p-3 text-center text-muted">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p class="mb-0">No applications yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Saved Opportunities -->
        <div class="card mb-3">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Closing Soon</h5>
                    <a href="{{ route('opportunities.index') }}" class="btn btn-sm btn-outline-primary">Browse</a>
                </div>
            </div>
            
            <div class="card-body p-0">
                @forelse($savedOpportunities as $opportunity)
                    <div class="border-bottom p-3" style="border-bottom: 1px solid #dee2e6;">
                        <a href="{{ route('opportunities.show', $opportunity) }}" class="text-decoration-none">
                            <h6 class="mb-1">{{ $opportunity->title }}</h6>
                        </a>
                        <p class="text-muted small mb-2">{{ $opportunity->provider->company_name }}</p>
                        <div class="alert alert-warning py-1 px-2 mb-0 small">
                            <i class="fas fa-clock"></i> {{ $opportunity->daysUntilDeadline() }} days left
                        </div>
                    </div>
                @empty
                    <div class="p-3 text-center text-muted">
                        <i class="fas fa-bookmark fa-2x mb-2"></i>
                        <p class="mb-0">No saved opportunities</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Notifications -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Notifications</h5>
                    <a href="{{ route('applications.index') }}" class="btn btn-sm btn-outline-primary">All</a>
                </div>
            </div>
            
            <div class="card-body p-0">
                @forelse($notifications as $notif)
                    <div class="border-bottom p-3" style="border-bottom: 1px solid #dee2e6;">
                        <div class="d-flex gap-2">
                            <div>
                                <i class="fas fa-{{ $notif->getIconClass() }} text-primary fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 small">{{ $notif->title }}</h6>
                                <p class="text-muted small mb-0">{{ substr($notif->message, 0, 80) }}...</p>
                                <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-3 text-center text-muted">
                        <i class="fas fa-bell fa-2x mb-2"></i>
                        <p class="mb-0">No new notifications</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Profile Completion Alert -->
@php
    $profile = auth()->user()->studentProfile;
    $completeness = 0;
    if ($profile->student_id) $completeness += 25;
    if ($profile->course) $completeness += 25;
    if ($profile->resume) $completeness += 25;
    if ($profile->skills) $completeness += 25;
@endphp

@if($completeness < 100)
    <div class="alert alert-info mt-4" role="alert">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-2"><i class="fas fa-info-circle"></i> Complete Your Profile</h6>
                <p class="mb-0 small">Complete your profile to get more relevant opportunities</p>
            </div>
            <div class="text-end">
                <div class="mb-2">
                    <div class="progress" style="height: 20px; width: 150px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $completeness }}%;" 
                             aria-valuenow="{{ $completeness }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $completeness }}%
                        </div>
                    </div>
                </div>
                <a href="{{ route('profile.student.edit') }}" class="btn btn-sm btn-primary">Update Profile</a>
            </div>
        </div>
    </div>
@endif
@endsection
