<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Listing;
use App\Models\Message;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $stats = [
            'my_listings'     => $user->listings()->count(),
            'unread'          => Message::where('recipient_id', $user->id)->whereNull('read_at')->count(),
            'my_applications' => Application::where('user_id', $user->id)->count(),
            'applicants'      => Application::whereHas('post', fn ($q) => $q->where('user_id', $user->id))->count(),
        ];

        $openOpportunities = Post::opportunities()->open()
            ->with('user:id,name,member_type')
            ->withCount('applications')
            ->latest()
            ->take(6)
            ->get();

        $appliedPostIds = Application::where('user_id', $user->id)->pluck('post_id')->all();

        $recentPosts = Post::with('user:id,name,member_type')->latest()->take(5)->get();

        $recentListings = Listing::with('user:id,name')->available()->latest()->take(4)->get();

        return view('dashboard', compact(
            'user', 'stats', 'openOpportunities', 'appliedPostIds', 'recentPosts', 'recentListings'
        ));
    }
}