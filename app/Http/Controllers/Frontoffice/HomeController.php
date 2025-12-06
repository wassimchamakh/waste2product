<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Project;
use App\Models\Dechet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display the home page with real data from database
     */
    public function index()
    {
        // Get statistics from database
        $stats = [
            'co2_saved' => $this->calculateCO2Saved(),
            'projects_count' => Project::where('status', 'published')->count(),
            'wastes_count' => Dechet::where('status', 'available')->count(),
            'users_count' => User::count(),
        ];

        // Get popular projects (by likes or views)
        $popularProjects = Project::where('status', 'published')
            ->with('user')
            ->withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->orderBy('views_count', 'desc')
            ->take(3)
            ->get()
            ->map(function($project) {
                return [
                    'id' => $project->id,
                    'title' => $project->title,
                    'difficulty' => $project->difficulty_level ?? 'Moyen',
                    'difficulty_color' => $this->getDifficultyColor($project->difficulty_level ?? 'Moyen'),
                    'duration' => $project->estimated_time ?? '4',
                    'creator' => $project->user->name ?? 'Anonyme',
                    'city' => 'Tunisie',
                    'rating' => $project->average_rating ?? 0,
                    'reviews_count' => $project->reviews_count ?? 0,
                    'likes_count' => $project->likes_count ?? 0,
                    'image' => $project->photo ? asset('uploads/projects/' . $project->photo) : null,
                    'icon' => 'hammer'
                ];
            });

        // If no projects, provide sample data
        if ($popularProjects->isEmpty()) {
            $popularProjects = collect([
                [
                    'id' => 0,
                    'title' => 'Créez votre premier projet',
                    'difficulty' => 'Facile',
                    'difficulty_color' => 'success',
                    'duration' => '2h',
                    'creator' => 'Équipe Waste2Product',
                    'city' => 'Tunisie',
                    'rating' => 5.0,
                    'reviews_count' => 0,
                    'likes_count' => 0,
                    'image' => null,
                    'icon' => 'hammer'
                ]
            ]);
        }

        // Get recent wastes
        $recentWastes = Dechet::where('status', 'available')
            ->with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get()
            ->map(function($dechet) {
                return [
                    'id' => $dechet->id,
                    'title' => $dechet->title,
                    'location' => $dechet->location ?? 'Tunisie',
                    'time_ago' => $dechet->created_at->diffForHumans(),
                    'quantity' => $dechet->quantity ?? 'N/A',
                    'image' => $dechet->photo ? asset('uploads/dechets/' . $dechet->photo) : null,
                    'icon' => $this->getWasteIcon($dechet->category->name ?? 'autre')
                ];
            });

        // If no wastes, provide sample data
        if ($recentWastes->isEmpty()) {
            $recentWastes = collect([
                [
                    'id' => 0,
                    'title' => 'Déclarez votre premier déchet',
                    'location' => 'Tunisie',
                    'time_ago' => 'Commencez maintenant',
                    'quantity' => 'N/A',
                    'image' => null,
                    'icon' => 'recycle'
                ]
            ]);
        }

        // Get upcoming events
        $upcomingEvents = Event::where('status', 'published')
            ->where('date_start', '>', now())
            ->orderBy('date_start', 'asc')
            ->take(3)
            ->get()
            ->map(function($event) {
                $currentParticipants = $event->participants()
                    ->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])
                    ->count();
                    
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'date' => $event->date_start->format('d'),
                    'month' => strtoupper($event->date_start->translatedFormat('M')),
                    'time_location' => $event->date_start->format('l H:i') . ' • ' . $event->location,
                    'available_spots' => max(0, $event->max_participants - $currentParticipants),
                    'color' => $this->getEventColor($event->type ?? 'workshop'),
                    'type' => $event->type
                ];
            });

        // If no events, provide sample data
        if ($upcomingEvents->isEmpty()) {
            $upcomingEvents = collect([
                [
                    'id' => 0,
                    'title' => 'Créez votre premier événement',
                    'date' => now()->addDays(7)->format('d'),
                    'month' => strtoupper(now()->addDays(7)->translatedFormat('M')),
                    'time_location' => 'Bientôt disponible',
                    'available_spots' => 0,
                    'color' => 'primary',
                    'type' => 'workshop'
                ]
            ]);
        }

        // Get featured tutorials (if exists)
        $featuredTutorials = [];
        if (class_exists('App\Models\Tutorial')) {
            $featuredTutorials = \App\Models\Tutorial::where('status', 'published')
                ->orderBy('views_count', 'desc')
                ->take(3)
                ->get();
        }

        // Get testimonials (sample data - can be from database later)
        $testimonials = [
            [
                'name' => 'Ahmed Ben Salem',
                'location' => 'Tunis • Créateur de projets',
                'content' => 'Grâce à Waste2Product, j\'ai transformé 20 palettes en mobilier design ! Ma maison a un style unique et j\'ai économisé plus de 800 DT.',
                'rating' => 5
            ],
            [
                'name' => 'Sarah Mansouri',
                'location' => 'Sfax • Collectionneuse',
                'content' => 'Je collecte les bouteilles plastique dans mon quartier. La plateforme m\'aide à les valoriser et sensibiliser ma communauté !',
                'rating' => 5
            ]
        ];

        return view('FrontOffice.home', compact(
            'stats',
            'popularProjects',
            'recentWastes',
            'upcomingEvents',
            'featuredTutorials',
            'testimonials'
        ));
    }

    /**
     * Display authenticated user dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // User's statistics
        $userStats = [
            'projects_created' => Project::where('user_id', $user->id)->count(),
            'wastes_posted' => Dechet::where('user_id', $user->id)->count(),
            'events_joined' => $user->participants()->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])->count(),
            'likes_received' => Project::where('user_id', $user->id)->withCount('likes')->get()->sum('likes_count'),
        ];

        // Recent activity
        $recentProjects = Project::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $myWastes = Dechet::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $upcomingEvents = $user->participants()
            ->with('event')
            ->whereHas('event', function($query) {
                $query->where('date_start', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(fn($p) => $p->event);

        return view('FrontOffice.pages.homestats', compact(
            'userStats',
            'recentProjects',
            'myWastes',
            'upcomingEvents'
        ));
    }

    /**
     * Calculate estimated CO2 saved based on waste recycled
     */
    private function calculateCO2Saved()
    {
        // Simplified calculation: each kg of waste recycled saves ~2.5kg CO2
        $totalWasteKg = Dechet::where('status', '!=', 'available')
            ->sum('quantity');
        
        return round($totalWasteKg * 2.5);
    }

    /**
     * Get difficulty color
     */
    private function getDifficultyColor($difficulty)
    {
        return match(strtolower($difficulty)) {
            'facile', 'easy' => 'success',
            'difficile', 'hard' => 'accent',
            'intermédiaire', 'intermediate', 'moyen', 'medium' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get waste icon
     */
    private function getWasteIcon($category)
    {
        return match(strtolower($category)) {
            'plastique', 'plastic' => 'wine-bottle',
            'bois', 'wood' => 'pallet',
            'metal' => 'cog',
            'verre', 'glass' => 'glass-whiskey',
            'textile' => 'tshirt',
            'électronique', 'electronic' => 'laptop',
            'pneus', 'tires' => 'tire',
            default => 'box'
        };
    }

    /**
     * Get event color
     */
    private function getEventColor($type)
    {
        return match(strtolower($type)) {
            'workshop', 'atelier' => 'primary',
            'collection', 'collecte' => 'success',
            'training', 'formation' => 'secondary',
            'repair_cafe' => 'accent',
            default => 'primary'
        };
    }
}
