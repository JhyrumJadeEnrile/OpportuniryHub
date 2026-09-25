<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        $user = auth()->user();
        $profile = $user->isStudent() ? $user->studentProfile : $user->providerProfile;

        return view('profile.edit', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function editStudent()
    {
        $this->authorize('student', auth()->user());

        return view('profile.student-edit', [
            'profile' => auth()->user()->studentProfile,
        ]);
    }

    public function updateStudent(Request $request)
    {
        $this->authorize('student', auth()->user());

        $validated = $request->validate([
            'student_id' => 'nullable|string|max:20',
            'course' => 'nullable|string|max:100',
            'year_level' => 'nullable|string|in:1st Year,2nd Year,3rd Year,4th Year',
            'gpa' => 'nullable|numeric|between:1,4',
            'skills' => 'nullable|string',
            'interests' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'graduation_date' => 'nullable|date',
        ]);

        $profile = auth()->user()->studentProfile;

        if ($request->hasFile('resume')) {
            if ($profile->resume) {
                Storage::disk('public')->delete($profile->resume);
            }
            $validated['resume'] = $request->file('resume')->store('resumes', 'public');
        }

        // Convert comma-separated values to arrays
        if (!empty($validated['skills'])) {
            $validated['skills'] = array_filter(array_map('trim', explode(',', $validated['skills'])));
        }
        if (!empty($validated['interests'])) {
            $validated['interests'] = array_filter(array_map('trim', explode(',', $validated['interests'])));
        }

        $profile->update($validated);

        return redirect()->back()->with('success', 'Student profile updated successfully!');
    }

    public function editProvider()
    {
        $this->authorize('provider', auth()->user());

        return view('profile.provider-edit', [
            'profile' => auth()->user()->providerProfile,
        ]);
    }

    public function updateProvider(Request $request)
    {
        $this->authorize('provider', auth()->user());

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_description' => 'nullable|string|max:2000',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'industry' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'company_address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
        ]);

        $profile = auth()->user()->providerProfile;

        if ($request->hasFile('company_logo')) {
            if ($profile->company_logo) {
                Storage::disk('public')->delete($profile->company_logo);
            }
            $validated['company_logo'] = $request->file('company_logo')->store('company-logos', 'public');
        }

        $profile->update($validated);

        return redirect()->back()->with('success', 'Provider profile updated successfully!');
    }
}
