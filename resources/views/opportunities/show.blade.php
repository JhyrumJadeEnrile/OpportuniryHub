@extends('layouts.app')

@section('title', $opportunity->title)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('opportunities.index') }}" class="text-decoration-none">
            <i class="fas fa-arrow-left"></i> Back to Opportunities
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Header Card -->
        <div class="card mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-start gap-3 mb-3">
                    @if($opportunity->featured_image)
                        <img src="{{ asset('storage/' . $opportunity->featured_image) }}" 
                             alt="{{ $opportunity->title }}" 
                             class="rounded" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #003E90 0%, #004ab8 100%); border-radius: 5px; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-briefcase fa-4x"></i>
                        </div>
                    @endif
                    
                    <div class="flex-grow-1">
                        <h1 class="mb-2">{{ $opportunity->title }}</h1>
                        
                        <p class="text-muted mb-2">
                            <i class="fas fa-building"></i> 
                            <strong>{{ $opportunity->provider->company_name }}</strong>
                        </p>
                        
                        <div class="d-flex gap-2 flex-wrap mb-3">
                            @php
                                $typeClass = match($opportunity->type) {
                                    'scholarship' => 'badge-scholarship',
                                    'internship' => 'badge-internship',
                                    'training' => 'badge-training',
                                    'job' => 'badge-job',
                                    default => 'badge-secondary',
                                };
                            @endphp
                            <span class="badge badge-opportunity-type {{ $typeClass }}">
                                {{ ucfirst($opportunity->type) }}
                            </span>
                            
                            @if($opportunity->location)
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-map-marker-alt"></i> {{ $opportunity->location }}
                                </span>
                            @endif
                            
                            @if($opportunity->is_featured)
                                <span class="badge bg-warning">
                                    <i class="fas fa-star"></i> Featured
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Description -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">About This Opportunity</h5>
            </div>
            <div class="card-body">
                {!! nl2br(e($opportunity->description)) !!}
            </div>
        </div>
        
        <!-- Details Grid -->
        <div class="row g-3 mb-4">
            @if($opportunity->salary_range)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-money-bill-wave text-success fa-2x mb-2"></i>
                            <h6 class="text-muted mb-2">Salary Range</h6>
                            <p class="fw-bold mb-0">{{ $opportunity->salary_range }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            @if($opportunity->duration)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-hourglass-half text-info fa-2x mb-2"></i>
                            <h6 class="text-muted mb-2">Duration</h6>
                            <p class="fw-bold mb-0">{{ $opportunity->duration }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-briefcase text-primary fa-2x mb-2"></i>
                        <h6 class="text-muted mb-2">Positions Available</h6>
                        <p class="fw-bold mb-0">{{ $opportunity->positions_available }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-users text-secondary fa-2x mb-2"></i>
                        <h6 class="text-muted mb-2">Applications</h6>
                        <p class="fw-bold mb-0">{{ $opportunity->applications_received }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Requirements -->
        @if($opportunity->requirements)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Requirements</h5>
                </div>
                <div class="card-body">
                    {!! nl2br(e($opportunity->requirements)) !!}
                </div>
            </div>
        @endif
        
        <!-- Benefits -->
        @if($opportunity->benefits)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Benefits</h5>
                </div>
                <div class="card-body">
                    {!! nl2br(e($opportunity->benefits)) !!}
                </div>
            </div>
        @endif
        
        <!-- Course Eligibility -->
        @if($opportunity->course_eligibility)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Course Eligibility</h5>
                </div>
                <div class="card-body">
                    <p>{{ $opportunity->course_eligibility }}</p>
                </div>
            </div>
        @endif
        
        <!-- Tags -->
        @if($opportunity->getTagsArray())
            <div class="mb-4">
                <h6 class="mb-2">Tags:</h6>
                @foreach($opportunity->getTagsArray() as $tag)
                    <span class="badge bg-light text-dark me-1">{{ $tag }}</span>
                @endforeach
            </div>
        @endif
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Deadline Card -->
        <div class="card mb-3">
            <div class="card-body">
                @php
                    $status = $opportunity->getDeadlineStatus();
                    $daysLeft = $opportunity->daysUntilDeadline();
                @endphp
                
                @if($status === 'closed')
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-times-circle"></i> <strong>This opportunity has closed</strong>
                    </div>
                @elseif($status === 'closing_today')
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-exclamation-circle"></i> <strong>Closing Today!</strong>
                    </div>
                @elseif($status === 'closing_soon')
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-clock"></i> <strong>{{ $daysLeft }} days remaining</strong>
                    </div>
                @else
                    <div class="text-center mb-3">
                        <h6 class="text-muted mb-2">Application Deadline</h6>
                        <h4 class="text-primary mb-0">
                            {{ $opportunity->application_deadline->format('M d, Y') }}
                        </h4>
                    </div>
                    <p class="text-muted text-center mb-0">{{ $daysLeft }} days remaining</p>
                @endif
            </div>
        </div>
        
        <!-- Action Buttons -->
        @auth
            @if(auth()->user()->isStudent())
                <div class="d-grid gap-2 mb-3">
                    @if(!$opportunity->isDeadlinePassed())
                        @if($hasApplied)
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle"></i> You have applied to this opportunity
                            </div>
                        @else
                            <a href="{{ route('applications.create', $opportunity) }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-send"></i> Apply Now
                            </a>
                        @endif
                    @else
                        <button class="btn btn-secondary btn-lg" disabled>
                            <i class="fas fa-times"></i> Deadline Passed
                        </button>
                    @endif
                    
                    <button type="button" class="btn btn-outline-primary" onclick="toggleSave({{ $opportunity->id }})">
                        <i class="fas fa-{{ $isSaved ? 'bookmark' : 'bookmark' }}"></i>
                        <span class="save-text">{{ $isSaved ? 'Saved' : 'Save Opportunity' }}</span>
                    </button>
                </div>
            @endif
        @else
            <div class="alert alert-info mb-3">
                <p class="mb-2"><strong>Want to apply?</strong></p>
                <p class="mb-0 small">Sign in to your account or create one to apply for this opportunity.</p>
            </div>
            <div class="d-grid gap-2">
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary">
                    <i class="fas fa-user-plus"></i> Create Account
                </a>
            </div>
        @endauth
        
        <!-- Stats Card -->
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="text-muted mb-3">Opportunity Info</h6>
                <div class="mb-2">
                    <small class="text-muted">Views</small>
                    <p class="mb-0"><i class="fas fa-eye"></i> {{ $opportunity->views_count }}</p>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Saves</small>
                    <p class="mb-0"><i class="fas fa-bookmark"></i> {{ $opportunity->saves_count }}</p>
                </div>
                <div>
                    <small class="text-muted">Posted</small>
                    <p class="mb-0">{{ $opportunity->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
        
        <!-- Provider Card -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">About the Provider</h6>
            </div>
            <div class="card-body">
                @if($opportunity->provider->company_logo)
                    <img src="{{ asset('storage/' . $opportunity->provider->company_logo) }}" 
                         alt="{{ $opportunity->provider->company_name }}" 
                         class="img-fluid rounded mb-3" style="height: 60px; object-fit: contain;">
                @endif
                
                <h6>{{ $opportunity->provider->company_name }}</h6>
                
                @if($opportunity->provider->company_description)
                    <p class="small text-muted mb-3">{{ substr($opportunity->provider->company_description, 0, 100) }}...</p>
                @endif
                
                <div class="small text-muted">
                    @if($opportunity->provider->industry)
                        <p class="mb-1"><strong>Industry:</strong> {{ $opportunity->provider->industry }}</p>
                    @endif
                    @if($opportunity->provider->website)
                        <p class="mb-1"><a href="{{ $opportunity->provider->website }}" target="_blank" class="text-primary">Visit Website</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
function toggleSave(opportunityId) {
    fetch(`/opportunities/${opportunityId}/save`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        const textSpan = document.querySelector('.save-text');
        const btn = event.target.closest('button');
        if (data.saved) {
            btn.classList.remove('btn-outline-primary');
            btn.classList.add('btn-primary');
            textSpan.textContent = 'Saved';
        } else {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline-primary');
            textSpan.textContent = 'Save Opportunity';
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endpush
@endsection
