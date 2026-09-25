<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\SavedOpportunity;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    public function index(Request $request)
    {
        $query = Opportunity::active()->where('published_at', '<=', now());

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Location filter
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereFullText(['title', 'description'], $search);
        }

        // Sort
        $sortBy = $request->get('sort', 'recent');
        $query = match($sortBy) {
            'deadline' => $query->orderBy('application_deadline', 'asc'),
            'popular' => $query->orderBy('views_count', 'desc'),
            'featured' => $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc'),
            'recent' => $query->orderBy('created_at', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $opportunities = $query->paginate(12);

        // If student, add saved status
        if (auth()->check() && auth()->user()->isStudent()) {
            $savedIds = auth()->user()->savedOpportunities()->pluck('opportunity_id');
            $opportunities->getCollection()->each(function ($opp) use ($savedIds) {
                $opp->is_saved = $savedIds->contains($opp->id);
            });
        }

        return view('opportunities.index', [
            'opportunities' => $opportunities,
            'types' => ['scholarship' => 'Scholarships', 'internship' => 'Internships', 'training' => 'Training', 'job' => 'Jobs'],
        ]);
    }

    public function show(Opportunity $opportunity)
    {
        // Check if published or if user is provider
        if ($opportunity->status !== 'active' && $opportunity->published_at > now()) {
            if (!auth()->check() || (auth()->user()->id !== $opportunity->provider->user_id && !auth()->user()->isAdmin())) {
                abort(404);
            }
        }

        $opportunity->increment('views_count');

        $isSaved = false;
        $hasApplied = false;

        if (auth()->check() && auth()->user()->isStudent()) {
            $isSaved = auth()->user()->savedOpportunities()->where('opportunity_id', $opportunity->id)->exists();
            $hasApplied = $opportunity->applications()
                ->where('student_id', auth()->id())
                ->exists();
        }

        return view('opportunities.show', [
            'opportunity' => $opportunity,
            'isSaved' => $isSaved,
            'hasApplied' => $hasApplied,
        ]);
    }

    public function toggleSave(Opportunity $opportunity)
    {
        if (!auth()->check() || !auth()->user()->isStudent()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $isSaved = auth()->user()->savedOpportunities()
            ->where('opportunity_id', $opportunity->id)
            ->exists();

        if ($isSaved) {
            auth()->user()->savedOpportunities()->detach($opportunity->id);
            $opportunity->decrement('saves_count');
            $saved = false;
        } else {
            auth()->user()->savedOpportunities()->attach($opportunity->id);
            $opportunity->increment('saves_count');
            $saved = true;
        }

        return response()->json([
            'saved' => $saved,
            'savesCount' => $opportunity->saves_count,
        ]);
    }

    public function search(Request $request)
    {
        $opportunities = Opportunity::active()
            ->where('published_at', '<=', now());

        if ($request->filled('q')) {
            $opportunities->whereFullText(['title', 'description'], $request->q);
        }

        if ($request->filled('type')) {
            $opportunities->where('type', $request->type);
        }

        if ($request->filled('location')) {
            $opportunities->where('location', 'like', '%' . $request->location . '%');
        }

        return response()->json($opportunities->get()->map(fn($opp) => [
            'id' => $opp->id,
            'title' => $opp->title,
            'type' => $opp->type,
            'location' => $opp->location,
            'deadline' => $opp->application_deadline->format('M d, Y'),
        ]));
    }
}
