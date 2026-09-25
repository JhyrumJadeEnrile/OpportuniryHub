<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\Application;
use App\Models\DeadlineReminder;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('student', only: ['create', 'store', 'myApplications']);
    }

    public function create(Opportunity $opportunity)
    {
        if ($opportunity->isDeadlinePassed()) {
            return redirect()->back()->with('error', 'Application deadline has passed.');
        }

        $existing = Application::where('student_id', auth()->id())
            ->where('opportunity_id', $opportunity->id)
            ->exists();

        if ($existing) {
            return redirect()->back()->with('error', 'You have already applied to this opportunity.');
        }

        return view('applications.create', [
            'opportunity' => $opportunity,
        ]);
    }

    public function store(Opportunity $opportunity, Request $request)
    {
        if ($opportunity->isDeadlinePassed()) {
            return redirect()->back()->with('error', 'Application deadline has passed.');
        }

        $validated = $request->validate([
            'cover_letter' => 'required|string|min:50|max:5000',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'custom_answers' => 'nullable|array',
        ]);

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        }

        $application = Application::create([
            'student_id' => auth()->id(),
            'opportunity_id' => $opportunity->id,
            'cover_letter' => $validated['cover_letter'],
            'resume' => $resumePath,
            'application_answers' => $validated['custom_answers'] ?? null,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        // Create notification for provider
        Notification::create([
            'user_id' => $opportunity->provider->user_id,
            'type' => 'new_application',
            'title' => 'New Application Received',
            'message' => auth()->user()->name . ' applied to ' . $opportunity->title,
            'opportunity_id' => $opportunity->id,
            'application_id' => $application->id,
        ]);

        // Increment counts
        $opportunity->increment('applications_received');
        auth()->user()->studentProfile()->increment('applications_count');

        // Create deadline reminder if not saved
        if (!auth()->user()->savedOpportunities()->where('opportunity_id', $opportunity->id)->exists()) {
            $this->createDeadlineReminder(auth()->user(), $opportunity);
        }

        return redirect()->route('opportunities.show', $opportunity)
            ->with('success', 'Your application has been submitted successfully!');
    }

    public function myApplications()
    {
        $applications = Application::where('student_id', auth()->id())
            ->with('opportunity.provider')
            ->orderBy('submitted_at', 'desc')
            ->paginate(10);

        return view('applications.index', [
            'applications' => $applications,
        ]);
    }

    public function show(Application $application)
    {
        if ($application->student_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        return view('applications.show', [
            'application' => $application,
        ]);
    }

    public function withdraw(Application $application)
    {
        if ($application->student_id !== auth()->id()) {
            abort(403);
        }

        if ($application->status !== 'submitted') {
            return redirect()->back()->with('error', 'Cannot withdraw this application.');
        }

        $application->update(['status' => 'withdrawn']);

        return redirect()->back()->with('success', 'Application withdrawn successfully.');
    }

    private function createDeadlineReminder($student, Opportunity $opportunity)
    {
        $deadline = $opportunity->application_deadline;
        
        // Create reminder for 3 days before deadline
        $reminderDate = $deadline->copy()->subDays(3);
        
        if ($reminderDate->isFuture()) {
            DeadlineReminder::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'opportunity_id' => $opportunity->id,
                ],
                [
                    'reminder_type' => '3_days',
                    'send_at' => $reminderDate->startOfDay(),
                ]
            );
        }
    }
}
