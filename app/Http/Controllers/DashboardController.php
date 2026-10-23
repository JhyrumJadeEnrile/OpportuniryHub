<?php

namespace App\Http\Controllers;

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
            'my_listings' => $user->listings()->count(),
            'unread'      => Message::where('recipient_id', $user->id)->whereNull('read_at')->count(),
            'members'     => User::count(),
            'alumni'      => User::where('member_type', 'alumni')->count(),
            'fresh_grads' => User::where('member_type', 'fresh_grad')->count(),
        ];

        $recentPosts = Post::with('user:id,name,member_type')
            ->latest()
            ->take(5)
            ->get();

        $recentListings = Listing::with('user:id,name')
            ->available()
            ->latest()
            ->take(4)
            ->get();

        return view('dashboard', compact('user', 'stats', 'recentPosts', 'recentListings'));
    }
}
