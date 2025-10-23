<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\Event;
use App\Models\Dechet;
use App\Models\Tutorial;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics
     */
    public function index()
    {
        // Get current month and last month dates
        $currentMonthStart = Carbon::now()->startOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Total counts
        $stats = [
            'total_users' => User::count(),
            'total_projects' => Project::count(),
            'total_events' => Event::count(),
            'total_dechets' => Dechet::count(),
            'total_tutorials' => Tutorial::count(),
        ];

        // Current month counts
        $currentMonth = [
            'users' => User::where('created_at', '>=', $currentMonthStart)->count(),
            'projects' => Project::where('created_at', '>=', $currentMonthStart)->count(),
            'events' => Event::where('created_at', '>=', $currentMonthStart)->count(),
            'dechets' => Dechet::where('created_at', '>=', $currentMonthStart)->count(),
        ];

        // Last month counts
        $lastMonth = [
            'users' => User::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count(),
            'projects' => Project::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count(),
            'events' => Event::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count(),
            'dechets' => Dechet::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count(),
        ];

        // Calculate percentage changes
        $changes = [
            'users' => $this->calculatePercentageChange($lastMonth['users'], $currentMonth['users']),
            'projects' => $this->calculatePercentageChange($lastMonth['projects'], $currentMonth['projects']),
            'events' => $this->calculatePercentageChange($lastMonth['events'], $currentMonth['events']),
            'dechets' => $this->calculatePercentageChange($lastMonth['dechets'], $currentMonth['dechets']),
        ];

        // Recent activities (last 10)
        $recentActivities = collect();

        // Recent users
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($user) {
                return [
                    'type' => 'user',
                    'icon' => 'fa-user-plus',
                    'color' => 'blue',
                    'message' => "Nouvel utilisateur: {$user->name}",
                    'time' => $user->created_at,
                ];
            });

        // Recent projects
        $recentProjects = Project::orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($project) {
                return [
                    'type' => 'project',
                    'icon' => 'fa-project-diagram',
                    'color' => 'green',
                    'message' => "Nouveau projet: {$project->title}",
                    'time' => $project->created_at,
                ];
            });

        // Recent events
        $recentEvents = Event::orderBy('created_at', 'desc')
            ->limit(2)
            ->get()
            ->map(function($event) {
                return [
                    'type' => 'event',
                    'icon' => 'fa-calendar-alt',
                    'color' => 'purple',
                    'message' => "Nouvel événement: {$event->title}",
                    'time' => $event->created_at,
                ];
            });

        $recentActivities = $recentActivities
            ->concat($recentUsers)
            ->concat($recentProjects)
            ->concat($recentEvents)
            ->sortByDesc('time')
            ->take(10);

        // Monthly data for charts (last 6 months)
        $monthlyData = $this->getMonthlyData();

        // User statistics
        $userStats = [
            'admins' => User::where('is_admin', true)->count(),
            'regular_users' => User::where('is_admin', false)->count(),
            'verified' => User::whereNotNull('email_verified_at')->count(),
            'unverified' => User::whereNull('email_verified_at')->count(),
        ];

        // Top contributors
        $topContributors = User::withCount(['projects', 'dechets'])
            ->orderByDesc('projects_count')
            ->limit(5)
            ->get();

        return view('BackOffice.home', compact(
            'stats',
            'changes',
            'recentActivities',
            'monthlyData',
            'userStats',
            'topContributors'
        ));
    }

    /**
     * Calculate percentage change between two values
     */
    private function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue > 0 ? 100 : 0;
        }
        
        $change = (($newValue - $oldValue) / $oldValue) * 100;
        return round($change, 1);
    }

    /**
     * Get monthly data for the last 6 months
     */
    private function getMonthlyData()
    {
        $months = [];
        $usersData = [];
        $projectsData = [];
        $eventsData = [];
        $dechetsData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $months[] = $date->format('M');
            
            $usersData[] = User::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $projectsData[] = Project::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $eventsData[] = Event::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $dechetsData[] = Dechet::whereBetween('created_at', [$monthStart, $monthEnd])->count();
        }

        return [
            'months' => $months,
            'users' => $usersData,
            'projects' => $projectsData,
            'events' => $eventsData,
            'dechets' => $dechetsData,
        ];
    }
}
