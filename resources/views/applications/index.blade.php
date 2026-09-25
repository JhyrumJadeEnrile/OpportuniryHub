@extends('layouts.app')

@section('title', 'My Applications')

@section('content')
<div class="mb-4">
    <h2>
        <i class="fas fa-file-check"></i> My Applications
        <small class="text-muted">({{ $applications->total() }} total)</small>
    </h2>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="row g-3">
            @php
                $stats = [
                    ['icon' => 'file-check', 'label' => 'Submitted', 'count' => \App\Models\Application::where('student_id', auth()->id())->where('status', 'submitted')->count(), 'color' => 'info'],
                    ['icon' => 'hourglass-half', 'label' => 'Under Review', 'count' => \App\Models\Application::where('student_id', auth()->id())->where('status', 'under_review')->count(), 'color' => 'warning'],
                    ['icon' => 'star', 'label' => 'Shortlisted', 'count' => \App\Models\Application::where('student_id', auth()->id())->where('status', 'shortlisted')->count(), 'color' => 'success'],
                    ['icon' => 'check-circle', 'label' => 'Accepted', 'count' => \App\Models\Application::where('student_id', auth()->id())->where('status', 'accepted')->count(), 'color' => 'success'],
                    ['icon' => 'times-circle', 'label' => 'Rejected', 'count' => \App\Models\Application::where('student_id', auth()->id())->where('status', 'rejected')->count(), 'color' => 'danger'],
                ]
            @endphp
            
            @foreach($stats as $stat)
                <div class="col-md-3 col-6">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-{{ $stat['icon'] }} fa-2x text-{{ $stat['color'] }} mb-2"></i>
                            <h5>{{ $stat['count'] }}</h5>
                            <p class="text-muted small mb-0">{{ $stat['label'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="row g-3">
    @forelse($applications as $application)
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <!-- Title and Company -->
                            <a href="{{ route('applications.show', $application) }}" class="text-decoration-none">
                                <h5 class="card-title mb-1">{{ $application->opportunity->title }}</h5>
                            </a>
                            <p class="text-muted mb-3">
                                <i class="fas fa-building"></i> {{ $application->opportunity->provider->company_name }}
                            </p>
                            
                            <!-- Details -->
                            <div class="row text-muted small">
                                <div class="col-md-3 mb-2">
                                    <strong>Type:</strong><br>
                                    <span class="badge badge-opportunity-type" style="background-color: #e7f5ff; color: #1971c2;">
                                        {{ ucfirst($application->opportunity->type) }}
                                    </span>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <strong>Deadline:</strong><br>
                                    {{ $application->opportunity->application_deadline->format('M d, Y') }}
                                </div>
                                <div class="col-md-3 mb-2">
                                    <strong>Applied:</strong><br>
                                    {{ $application->submitted_at->diffForHumans() }}
                                </div>
                                <div class="col-md-3 mb-2">
                                    <strong>Status:</strong><br>
                                    @php
                                        $statusColors = [
                                            'submitted' => 'info',
                                            'under_review' => 'warning',
                                            'shortlisted' => 'success',
                                            'accepted' => 'success',
                                            'rejected' => 'danger',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$application->status] ?? 'secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex gap-2 flex-column">
                                <a href="{{ route('applications.show', $application) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                                
                                @if($application->status === 'submitted')
                                    <form method="POST" action="{{ route('applications.withdraw', $application) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('Are you sure you want to withdraw this application?')">
                                            <i class="fas fa-trash"></i> Withdraw
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <h5>No applications yet</h5>
                <p class="text-muted mb-3">You haven't applied to any opportunities yet.</p>
                <a href="{{ route('opportunities.index') }}" class="btn btn-primary">
                    <i class="fas fa-briefcase"></i> Browse Opportunities
                </a>
            </div>
        </div>
    @endforelse
    
    <!-- Pagination -->
    @if($applications->hasPages())
        <div class="col-12">
            <nav aria-label="Page navigation">
                {{ $applications->links() }}
            </nav>
        </div>
    @endif
</div>
@endsection
