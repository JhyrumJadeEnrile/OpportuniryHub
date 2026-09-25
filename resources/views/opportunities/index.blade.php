@extends('layouts.app')

@section('title', 'Browse Opportunities')

@section('content')
<div class="row mb-4">
    <div class="col-md-9">
        <h2>
            <i class="fas fa-briefcase"></i> Opportunities
            <small class="text-muted">({{ $opportunities->total() }} found)</small>
        </h2>
    </div>
    @auth
    <div class="col-md-3 text-end">
        @if(auth()->user()->isStudent())
            <a href="{{ route('applications.index') }}" class="btn btn-info">
                <i class="fas fa-file-check"></i> My Applications
            </a>
        @endif
    </div>
    @endauth
</div>

<div class="row g-4">
    <!-- Filters Sidebar -->
    <div class="col-lg-3">
        <div class="card sticky-top" style="top: 20px;">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-filter"></i> Filters
                </h5>
            </div>
            
            <form method="GET" action="{{ route('opportunities.index') }}" class="card-body">
                <!-- Search -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Search</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search opportunities..." value="{{ request('search') }}">
                </div>
                
                <!-- Type Filter -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Type</label>
                    @foreach($types as $value => $label)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" value="{{ $value }}" 
                                   id="type-{{ $value }}" {{ request('type') === $value ? 'checked' : '' }}>
                            <label class="form-check-label" for="type-{{ $value }}">
                                {{ $label }}
                            </label>
                        </div>
                    @endforeach
                </div>
                
                <!-- Location Filter -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Location</label>
                    <input type="text" name="location" class="form-control" 
                           placeholder="City or area..." value="{{ request('location') }}">
                </div>
                
                <!-- Sort -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Sort By</label>
                    <select name="sort" class="form-select">
                        <option value="recent" {{ request('sort') === 'recent' ? 'selected' : '' }}>Most Recent</option>
                        <option value="deadline" {{ request('sort') === 'deadline' ? 'selected' : '' }}>Deadline Soon</option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>Featured</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-search"></i> Apply Filters
                </button>
                
                <a href="{{ route('opportunities.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </form>
        </div>
    </div>
    
    <!-- Opportunities List -->
    <div class="col-lg-9">
        @forelse($opportunities as $opportunity)
            <div class="card mb-3 hover-shadow">
                <div class="card-body p-4">
                    <div class="row align-items-start">
                        <div class="col-md-9">
                            <!-- Header -->
                            <div class="d-flex align-items-start gap-3 mb-2">
                                @if($opportunity->featured_image)
                                    <img src="{{ asset('storage/' . $opportunity->featured_image) }}" 
                                         alt="{{ $opportunity->title }}" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                @else
                                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #003E90 0%, #004ab8 100%); border-radius: 5px; display: flex; align-items: center; justify-content: center; color: white;">
                                        <i class="fas fa-briefcase fa-2x"></i>
                                    </div>
                                @endif
                                
                                <div class="flex-grow-1">
                                    <a href="{{ route('opportunities.show', $opportunity) }}" class="text-decoration-none">
                                        <h5 class="card-title mb-1">{{ $opportunity->title }}</h5>
                                    </a>
                                    <p class="card-text text-muted mb-2">
                                        <i class="fas fa-building"></i> {{ $opportunity->provider->company_name }}
                                    </p>
                                    
                                    <div class="d-flex gap-2 flex-wrap mb-2">
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
                                    
                                    <p class="card-text text-muted small mb-0">
                                        {{ substr($opportunity->description, 0, 150) }}...
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 text-md-end">
                            <!-- Deadline -->
                            <div class="mb-3">
                                @php
                                    $status = $opportunity->getDeadlineStatus();
                                    $daysLeft = $opportunity->daysUntilDeadline();
                                @endphp
                                
                                @if($status === 'closed')
                                    <span class="badge bg-danger w-100">
                                        <i class="fas fa-times-circle"></i> Closed
                                    </span>
                                @elseif($status === 'closing_today')
                                    <span class="badge bg-danger w-100">
                                        <i class="fas fa-exclamation-circle"></i> Closing Today
                                    </span>
                                @elseif($status === 'closing_soon')
                                    <span class="badge bg-warning text-dark w-100">
                                        <i class="fas fa-clock"></i> {{ $daysLeft }} days left
                                    </span>
                                @else
                                    <span class="badge bg-success w-100">
                                        <i class="fas fa-calendar"></i> Expires {{ $opportunity->application_deadline->format('M d, Y') }}
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 flex-column">
                                <a href="{{ route('opportunities.show', $opportunity) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                                
                                @auth
                                    @if(auth()->user()->isStudent())
                                        <button type="button" class="btn btn-outline-primary btn-sm save-btn" 
                                                data-opportunity-id="{{ $opportunity->id }}"
                                                onclick="toggleSave(this, {{ $opportunity->id }})">
                                            <i class="fas fa-{{ $opportunity->is_saved ?? false ? 'bookmark' : 'bookmark' }}"></i>
                                            <span class="save-text">{{ $opportunity->is_saved ?? false ? 'Saved' : 'Save' }}</span>
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-search fa-3x mb-3"></i>
                <h5>No opportunities found</h5>
                <p class="text-muted">Try adjusting your filters or search terms</p>
                <a href="{{ route('opportunities.index') }}" class="btn btn-primary">
                    <i class="fas fa-redo"></i> Clear Filters
                </a>
            </div>
        @endforelse
        
        <!-- Pagination -->
        @if($opportunities->hasPages())
            <nav aria-label="Page navigation" class="mt-4">
                {{ $opportunities->links() }}
            </nav>
        @endif
    </div>
</div>

@push('js')
<script>
function toggleSave(btn, opportunityId) {
    fetch(`/opportunities/${opportunityId}/save`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        const textSpan = btn.querySelector('.save-text');
        if (data.saved) {
            btn.classList.remove('btn-outline-primary');
            btn.classList.add('btn-primary');
            textSpan.textContent = 'Saved';
        } else {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline-primary');
            textSpan.textContent = 'Save';
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endpush
@endsection
