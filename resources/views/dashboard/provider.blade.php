@extends('layouts.app')

@section('title', 'Provider Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1">
            <i class="fas fa-wave"></i> Welcome, {{ auth()->user()->name }}!
        </h2>
        <p class="text-muted">Manage your opportunities and review applications</p>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="{{ route('provider.opportunities.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Post New Opportunity
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-briefcase fa-2x text-primary mb-2"></i>
                <h5>{{ $stats['opportunities'] }}</h5>
                <p class="text-muted small mb-0">Total Opportunities</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-6">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-play-circle fa-2x text-success mb-2"></i>
                <h5>{{ $stats['active'] }}</h5>
                <p class="text-muted small mb-0">Active Postings</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-6">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-file-check fa-2x text-info mb-2"></i>
                <h5>{{ $stats['applications'] }}</h5>
                <p class="text-muted small mb-0">Total Applications</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-6">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-hourglass-half fa-2x text-warning mb-2"></i>
                <h5>{{ $stats['unreviewed'] }}</h5>
                <p class="text-muted small mb-0">To Review</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Opportunities -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Your Opportunities</h5>
                    <a href="{{ route('provider.opportunities.index') }}" class="btn btn-sm btn-outline-primary">Manage All</a>
                </div>
            </div>
            
            <div class="card-body p-0">
                @forelse($opportunities as $opportunity)
                    <div class="border-bottom p-3" style="border-bottom: 1px solid #dee2e6;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <a href="{{ route('provider.opportunities.show', $opportunity) }}" class="text-decoration-none">
                                    <h6 class="mb-1">{{ $opportunity->title }}</h6>
                                </a>
                                <div class="d-flex gap-2 mb-2">
                                    <span class="badge badge-opportunity-type" style="background-color: #e7f5ff; color: #1971c2; font-size: 0.75rem;">
                                        {{ ucfirst($opportunity->type) }}
                                    </span>
                                    @if($opportunity->status === 'active')
                                        <span class="badge bg-success" style="font-size: 0.75rem;">Active</span>
                                    @elseif($opportunity->status === 'draft')
                                        <span class="badge bg-secondary" style="font-size: 0.75rem;">Draft</span>
                                    @else
                                        <span class="badge bg-danger" style="font-size: 0.75rem;">Closed</span>
                                    @endif
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-file-check"></i> {{ $opportunity->applications_received }} applications
                                    • 
                                    <i class="fas fa-eye"></i> {{ $opportunity->views_count }} views
                                </small>
                            </div>
                            <a href="{{ route('provider.opportunities.show', $opportunity) }}" class="btn btn-sm btn-outline-primary">
                                View
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-3 text-center text-muted">
                        <i class="fas fa-briefcase fa-2x mb-2"></i>
                        <p class="mb-0">No opportunities posted yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Recent Applications -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Applications</h5>
            </div>
            
            <div class="card-body p-0">
                @forelse($applications as $application)
                    <div class="border-bottom p-3" style="border-bottom: 1px solid #dee2e6;">
                        <div>
                            <a href="{{ route('applications.show', $application) }}" class="text-decoration-none">
                                <h6 class="mb-1 small">{{ $application->student->name }}</h6>
                            </a>
                            <p class="text-muted small mb-2">{{ $application->opportunity->title }}</p>
                            @php
                                $statusColors = [
                                    'submitted' => 'info',
                                    'under_review' => 'warning',
                                    'shortlisted' => 'success',
                                    'accepted' => 'success',
                                    'rejected' => 'danger',
                                ];
                            @endphp
                            <div class="d-flex gap-2 justify-content-between align-items-center">
                                <span class="badge bg-{{ $statusColors[$application->status] ?? 'secondary' }}" style="font-size: 0.75rem;">
                                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                </span>
                                <small class="text-muted">{{ $application->submitted_at->diffForHumans() }}</small>
                            </div>
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
</div>
@endsection
