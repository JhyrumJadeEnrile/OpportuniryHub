<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->isStudent()) {
            return $this->studentDashboard($user);
        } elseif ($user->isProvider()) {
            return $this->providerDashboard($user);
        } elseif ($user->isAdmin()) {
            return $this->adminDashboard($user);
        }

        return view('dashboard.guest');
    }

    private function studentDashboard($user)
    {
        $profile = $user->studentProfile;
        
        $stats = [
            'applications' => $user->applications()->count(),
            'saved' => $user->savedOpportunities()->count(),
            'accepted' => $user->applications()->where('status', 'accepted')->count(),
            'shortlisted' => $user->applications()->where('status', 'shortlisted')->count(),
        ];

        $recentApplications = $user->applications()
            ->with('opportunity.provider')
            ->latest()
            ->limit(5)
            ->get();

        $savedOpportunities = $user->savedOpportunities()
            ->active()
            ->closingSoon()
            ->limit(5)
            ->get();

        $unreadNotifications = $user->unreadNotifications()
            ->limit(10)
            ->get();

        return view('dashboard.student', [
            'profile' => $profile,
            'stats' => $stats,
            'recentApplications' => $recentApplications,
            'savedOpportunities' => $savedOpportunities,
            'notifications' => $unreadNotifications,
        ]);
    }

    private function providerDashboard($user)
    {
        $profile = $user->providerProfile;

        $stats = [
            'opportunities' => $profile->opportunities()->count(),
            'active' => $profile->opportunities()->where('status', 'active')->count(),
            'applications' => Application::whereIn('opportunity_id', 
                $profile->opportunities()->pluck('id'))->count(),
            'unreviewed' => Application::whereIn('opportunity_id', 
                $profile->opportunities()->pluck('id'))
                ->where('status', 'submitted')->count(),
        ];

        $recentOpportunities = $profile->opportunities()
            ->latest()
            ->limit(5)
            ->get();

        $recentApplications = Application::whereIn('opportunity_id', 
            $profile->opportunities()->pluck('id'))
            ->with('student', 'opportunity')
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard.provider', [
            'profile' => $profile,
            'stats' => $stats,
            'opportunities' => $recentOpportunities,
            'applications' => $recentApplications,
        ]);
    }

    private function adminDashboard($user)
    {
        $stats = [
            'total_users' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'providers' => User::where('role', 'provider')->count(),
            'total_opportunities' => Opportunity::count(),
            'active_opportunities' => Opportunity::where('status', 'active')->count(),
            'total_applications' => Application::count(),
            'pending_applications' => Application::where('status', 'submitted')->count(),
        ];

        $recentUsers = User::latest()->limit(10)->get();
        $recentApplications = Application::with('student', 'opportunity')
            ->latest()
            ->limit(10)
            ->get();

        $opportunitiesByType = Opportunity::selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->get();

        $applicationsByStatus = Application::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        return view('dashboard.admin', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentApplications' => $recentApplications,
            'opportunitiesByType' => $opportunitiesByType,
            'applicationsByStatus' => $applicationsByStatus,
        ]);
    }

    public function markNotificationAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsAsRead()
    {
        auth()->user()->unreadNotifications()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
