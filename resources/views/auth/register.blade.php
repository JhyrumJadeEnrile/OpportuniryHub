@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-lg">
            <div class="card-header text-center">
                <h4 class="mb-0">
                    <i class="fas fa-user-plus"></i> Create Your Account
                </h4>
            </div>
            
            <div class="card-body p-4">
                <p class="text-muted text-center mb-4">Join OpportunityHub and start your journey</p>
                
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number (Optional)</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">I am a...</label>
                        <select class="form-select @error('role') is-invalid @enderror" 
                                id="role" name="role" required onchange="updateRoleInfo()">
                            <option value="">-- Select Role --</option>
                            <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>
                                <i class="fas fa-graduation-cap"></i> Student
                            </option>
                            <option value="provider" {{ old('role') === 'provider' ? 'selected' : '' }}>
                                <i class="fas fa-building"></i> Provider / Organization
                            </option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div id="role-info" class="alert alert-info mb-3" style="display: none;">
                        <div id="student-info">
                            <strong><i class="fas fa-graduation-cap"></i> Student Account</strong>
                            <p class="mb-0 small mt-2">Access scholarships, internships, training programs, and job opportunities. Track your applications and receive deadline reminders.</p>
                        </div>
                        <div id="provider-info" style="display: none;">
                            <strong><i class="fas fa-building"></i> Provider Account</strong>
                            <p class="mb-0 small mt-2">Post opportunities for scholarships, internships, training, or jobs. Manage applications and connect with talented students.</p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-1">Minimum 8 characters</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                               id="password_confirmation" name="password_confirmation" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="agree" name="agree" required>
                        <label class="form-check-label" for="agree">
                            I agree to the Terms of Service and Privacy Policy
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-user-plus"></i> Create Account
                    </button>
                </form>
                
                <div class="text-center">
                    <p class="text-muted mb-0">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-primary fw-bold">Sign in here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateRoleInfo() {
    const role = document.getElementById('role').value;
    const roleInfo = document.getElementById('role-info');
    const studentInfo = document.getElementById('student-info');
    const providerInfo = document.getElementById('provider-info');
    
    if (role) {
        roleInfo.style.display = 'block';
        if (role === 'student') {
            studentInfo.style.display = 'block';
            providerInfo.style.display = 'none';
        } else {
            studentInfo.style.display = 'none';
            providerInfo.style.display = 'block';
        }
    } else {
        roleInfo.style.display = 'none';
    }
}

// Show role info on page load if role is selected
document.addEventListener('DOMContentLoaded', updateRoleInfo);
</script>
@endsection
