@extends('layouts.app')

@section('title', 'Apply for ' . $opportunity->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-lg">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-file-upload"></i> Apply for Opportunity
                </h4>
            </div>
            
            <div class="card-body p-4">
                <!-- Opportunity Summary -->
                <div class="alert alert-info mb-4">
                    <h6 class="mb-2">{{ $opportunity->title }}</h6>
                    <p class="mb-1">
                        <strong>Company:</strong> {{ $opportunity->provider->company_name }}
                    </p>
                    <p class="mb-0">
                        <strong>Deadline:</strong> {{ $opportunity->application_deadline->format('M d, Y') }}
                        @php
                            $daysLeft = $opportunity->daysUntilDeadline();
                        @endphp
                        @if($daysLeft > 0)
                            <span class="badge bg-success">{{ $daysLeft }} days left</span>
                        @endif
                    </p>
                </div>
                
                <form method="POST" action="{{ route('applications.store', $opportunity) }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Cover Letter -->
                    <div class="mb-4">
                        <label for="cover_letter" class="form-label fw-bold">
                            Cover Letter <span class="text-danger">*</span>
                        </label>
                        <p class="text-muted small mb-2">Tell the provider why you're interested in this opportunity (minimum 50 characters)</p>
                        <textarea class="form-control @error('cover_letter') is-invalid @enderror" 
                                  id="cover_letter" name="cover_letter" rows="6" 
                                  placeholder="Write your cover letter here..." required>{{ old('cover_letter') }}</textarea>
                        <small class="d-block mt-2">
                            <span id="char-count">0</span> / 5000 characters
                        </small>
                        @error('cover_letter')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Resume Upload -->
                    <div class="mb-4">
                        <label for="resume" class="form-label fw-bold">Resume (Optional)</label>
                        <p class="text-muted small mb-2">Upload PDF, DOC, or DOCX file (Max 5MB)</p>
                        <div class="input-group">
                            <input type="file" class="form-control @error('resume') is-invalid @enderror" 
                                   id="resume" name="resume" accept=".pdf,.doc,.docx">
                            @error('resume')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Student Profile Preview -->
                    <div class="card mb-4 bg-light">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Your Profile Information</h6>
                        </div>
                        <div class="card-body">
                            @php
                                $profile = auth()->user()->studentProfile;
                            @endphp
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">Name</small>
                                    <p class="mb-0"><strong>{{ auth()->user()->name }}</strong></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">Email</small>
                                    <p class="mb-0"><strong>{{ auth()->user()->email }}</strong></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">Student ID</small>
                                    <p class="mb-0"><strong>{{ $profile->student_id ?? 'Not provided' }}</strong></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">Course</small>
                                    <p class="mb-0"><strong>{{ $profile->course ?? 'Not provided' }}</strong></p>
                                </div>
                            </div>
                            
                            <p class="small text-muted mb-0">
                                <a href="{{ route('profile.student.edit') }}">Update your profile</a> to ensure your information is complete
                            </p>
                        </div>
                    </div>
                    
                    <!-- Submission Info -->
                    <div class="alert alert-secondary mb-4">
                        <h6 class="mb-2">Before You Submit</h6>
                        <ul class="mb-0 small">
                            <li>Review your cover letter for spelling and grammar</li>
                            <li>Make sure your profile is complete and up to date</li>
                            <li>You can only submit one application per opportunity</li>
                            <li>You will receive updates on your application status</li>
                        </ul>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane"></i> Submit Application
                        </button>
                        <a href="{{ route('opportunities.show', $opportunity) }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('cover_letter');
    const charCount = document.getElementById('char-count');
    
    textarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
});
</script>
@endpush
@endsection
