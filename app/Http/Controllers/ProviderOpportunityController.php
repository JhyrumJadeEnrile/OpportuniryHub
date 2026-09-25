<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProviderOpportunityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('provider');
    }

    public function index()
    {
        $provider = auth()->user()->providerProfile;
        $opportunities = $provider->opportunities()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('provider.opportunities.index', [
            'opportunities' => $opportunities,
        ]);
    }

    public function create()
    {
        return view('provider.opportunities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'type' => 'required|in:scholarship,internship,training,job',
            'location' => 'nullable|string|max:255',
            'salary_range' => 'nullable|string|max:100',
            'duration' => 'nullable|string|max:100',
            'positions_available' => 'required|integer|min:1|max:100',
            'application_deadline' => 'required|date|after:today',
            'start_date' => 'nullable|date|after:today',
            'end_date' => 'nullable|date|after:start_date',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'course_eligibility' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'is_featured' => 'nullable|boolean',
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('opportunity-images', 'public');
        }

        $validated['provider_id'] = auth()->user()->providerProfile->id;
        $validated['status'] = 'draft';
        $validated['published_at'] = now();

        $opportunity = Opportunity::create($validated);

        return redirect()->route('provider.opportunities.show', $opportunity)
            ->with('success', 'Opportunity created successfully!');
    }

    public function show(Opportunity $opportunity)
    {
        $this->authorize('update', $opportunity);

        $applications = $opportunity->applications()
            ->with('student.studentProfile')
            ->latest()
            ->paginate(20);

        return view('provider.opportunities.show', [
            'opportunity' => $opportunity,
            'applications' => $applications,
        ]);
    }

    public function edit(Opportunity $opportunity)
    {
        $this->authorize('update', $opportunity);

        return view('provider.opportunities.edit', [
            'opportunity' => $opportunity,
        ]);
    }

    public function update(Opportunity $opportunity, Request $request)
    {
        $this->authorize('update', $opportunity);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'type' => 'required|in:scholarship,internship,training,job',
            'location' => 'nullable|string|max:255',
            'salary_range' => 'nullable|string|max:100',
            'duration' => 'nullable|string|max:100',
            'positions_available' => 'required|integer|min:1|max:100',
            'application_deadline' => 'required|date|after:today',
            'start_date' => 'nullable|date|after:today',
            'end_date' => 'nullable|date|after:start_date',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'course_eligibility' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:draft,active,closed',
        ]);

        if ($request->hasFile('featured_image')) {
            if ($opportunity->featured_image) {
                Storage::disk('public')->delete($opportunity->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('opportunity-images', 'public');
        }

        $opportunity->update($validated);

        return redirect()->back()->with('success', 'Opportunity updated successfully!');
    }

    public function close(Opportunity $opportunity)
    {
        $this->authorize('update', $opportunity);

        $opportunity->update(['status' => 'closed']);

        return redirect()->back()->with('success', 'Opportunity closed successfully!');
    }

    public function destroy(Opportunity $opportunity)
    {
        $this->authorize('delete', $opportunity);

        if ($opportunity->featured_image) {
            Storage::disk('public')->delete($opportunity->featured_image);
        }

        $opportunity->delete();

        return redirect()->route('provider.opportunities.index')
            ->with('success', 'Opportunity deleted successfully!');
    }

    public function updateApplicationStatus(Request $request, Opportunity $opportunity)
    {
        $this->authorize('update', $opportunity);

        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'status' => 'required|in:submitted,under_review,shortlisted,rejected,accepted',
            'notes' => 'nullable|string|max:1000',
        ]);

        $application = $opportunity->applications()->findOrFail($validated['application_id']);
        
        $application->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['notes'] ?? $application->admin_notes,
            'reviewed_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
