<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use App\Http\Requests\EventRequest;
use App\Mail\ParticipantNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /evenements
     */
    public function index(Request $request)
    {
        // Types d'événements avec configuration
        $types = [
            'workshop' => [
                'label' => '🛠️ Workshop',
                'icon' => 'fas fa-tools'
            ],
            'collection' => [
                'label' => '🌱 Collection',
                'icon' => 'fas fa-recycle'
            ],
            'training' => [
                'label' => '📚 Formation',
                'icon' => 'fas fa-graduation-cap'
            ],
            'repair_cafe' => [
                'label' => '☕ Repair Café',
                'icon' => 'fas fa-coffee'
            ]
        ];

        // Villes tunisiennes
        $cities = [
            'tunis' => 'Tunis',
            'ariana' => 'Ariana',
            'ben_arous' => 'Ben Arous',
            'la_manouba' => 'La Manouba',
            'la_marsa' => 'La Marsa',
            'sfax' => 'Sfax',
            'sousse' => 'Sousse',
            'kairouan' => 'Kairouan',
            'bizerte' => 'Bizerte',
            'gabes' => 'Gabès',
            'monastir' => 'Monastir',
            'nabeul' => 'Nabeul',
            'hammamet' => 'Hammamet',
            'gafsa' => 'Gafsa',
            'medenine' => 'Médenine',
            'kasserine' => 'Kasserine'
        ];

        // Récupération des événements avec relations
        $eventsQuery = Event::with(['organizer'])
            ->where('status', 'published');

        // Application des filtres
        $eventsQuery = $this->applyFilters($eventsQuery, $request);

        // Pagination
        $events = $eventsQuery->orderBy('date_start', 'asc')->paginate(12);

        // Événements populaires (triés par taux de remplissage)
        $popularEvents = Event::with(['organizer', 'participants'])  // ← Changé de 'user' à 'organizer'
        ->where('status', 'published')
        ->get()
        ->sortByDesc(function($event) {
            if ($event->max_participants == 0) return 0;
            return $event->participants->where('attendance_status', '!=', 'cancelled')->count() / $event->max_participants;
        })
        ->take(9);

        // Statistiques
        $stats = [
            'events_this_month' => Event::whereMonth('date_start', Carbon::now()->month)
                ->whereYear('date_start', Carbon::now()->year)
                ->count(),
            'total_participants' => Participant::where('attendance_status', '!=', 'cancelled')->count(),
            'upcoming_events' => Event::where('date_start', '>', Carbon::now())
                ->where('status', 'published')
                ->count()
        ];

        return view('FrontOffice.Events.index', compact(
            'events', 
            'popularEvents', 
            'types', 
            'cities', 
            'stats'
        ));
    }

    /**
     * Appliquer les filtres de recherche
     */
    private function applyFilters($query, $request)
    {
        // Recherche par titre
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filtre par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtre par ville
        if ($request->filled('city')) {
            $query->where('location', 'like', '%' . $request->city . '%');
        }

        // Gratuit uniquement
        if ($request->boolean('free_only')) {
            $query->where('price', 0);
        }

        // Masquer événements complets
        if ($request->boolean('hide_full', false)) {
            $query->whereRaw('(SELECT COUNT(*) FROM participants WHERE participants.event_id = events.id AND participants.attendance_status != "cancelled") < events.max_participants');
        }

        // Filtre par date
        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('date_start', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('date_start', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'month':
                    $query->whereMonth('date_start', Carbon::now()->month)
                          ->whereYear('date_start', Carbon::now()->year);
                    break;
            }
        }

        return $query;
    }

   /**
 * Afficher les détails d'un événement
 * GET /evenements/{id}
 */
/**
 * Afficher les détails d'un événement
 * GET /evenements/{id}
 */
/**
 * Afficher les détails d'un événement
 * GET /evenements/{id}
 */
public function show($id)
{
    $types = [
        'workshop' => ['label' => '🛠️ Workshop', 'icon' => 'fas fa-tools'],
        'collection' => ['label' => '🌱 Collection', 'icon' => 'fas fa-recycle'],
        'training' => ['label' => '📚 Formation', 'icon' => 'fas fa-graduation-cap'],
        'repair_cafe' => ['label' => '☕ Repair Café', 'icon' => 'fas fa-coffee']
    ];

    // Load ALL participants including cancelled
    $event = Event::with(['organizer', 'participants.user'])
        ->findOrFail($id);

    $userId = auth()->id();
    
    $isParticipant = $event->participants()
        ->where('user_id', $userId)
        ->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])
        ->exists();

    $userParticipation = $event->participants()
        ->where('user_id', $userId)
        ->first();

    // Convert to object so you can use -> notation
    $stats = (object) [
        'current_participants' => $event->participants()
            ->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])
            ->count(),
        'confirmed' => $event->participants()->where('attendance_status', 'confirmed')->count(),
        'registered' => $event->participants()->where('attendance_status', 'registered')->count(),
        'attended' => $event->participants()->where('attendance_status', 'attended')->count(),
        'cancelled' => $event->participants()->where('attendance_status', 'cancelled')->count(),
        'remaining_seats' => $event->max_participants - $event->participants()
            ->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])
            ->count(),
        'is_full' => $event->isFull(),
        'fill_percentage' => $event->fill_percentage
    ];

    $similarEvents = Event::with(['organizer'])
        ->where('type', $event->type)
        ->where('id', '!=', $id)
        ->where('status', 'published')
        ->where('date_start', '>', now())
        ->take(3)
        ->get();

    $isOrganizer = $event->user_id == $userId;

    // Prepare participants data for JavaScript - include ALL participants
    $participantsData = $event->participants->map(function($participant) {
        return [
            'id' => $participant->id,
            'name' => $participant->user->name ?? 'Utilisateur inconnu',
            'email' => $participant->user->email ?? 'N/A',
            'phone' => $participant->user->phone ?? 'N/A',
            'registration_date' => $participant->registration_date->toISOString(),
            'status' => $participant->attendance_status,
            'rating' => $participant->rating,
            'feedback' => $participant->feedback,
        ];
    });

    return view('FrontOffice.Events.show', compact(
        'event',
        'types',
        'isParticipant',
        'userParticipation',
        'stats',
        'similarEvents',
        'isOrganizer',
        'participantsData'
    ));
}

/**
 * Get participants modal content for AJAX loading
 */
public function getParticipantsModalContent($id)
{
    $types = [
        'workshop' => ['label' => '🛠️ Workshop', 'icon' => 'fas fa-tools'],
        'collection' => ['label' => '🌱 Collection', 'icon' => 'fas fa-recycle'],
        'training' => ['label' => '📚 Formation', 'icon' => 'fas fa-graduation-cap'],
        'repair_cafe' => ['label' => '☕ Repair Café', 'icon' => 'fas fa-coffee']
    ];

    $event = Event::with(['organizer', 'participants.user'])->findOrFail($id);
    
    $userId = auth()->id();
    $isOrganizer = $event->user_id == $userId;
    
    // Check if user is organizer
    if (!$isOrganizer) {
        return response()->json(['error' => 'Non autorisé'], 403);
    }

    $stats = (object) [
        'current_participants' => $event->participants()
            ->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])
            ->count(),
        'confirmed' => $event->participants()->where('attendance_status', 'confirmed')->count(),
        'registered' => $event->participants()->where('attendance_status', 'registered')->count(),
        'attended' => $event->participants()->where('attendance_status', 'attended')->count(),
        'cancelled' => $event->participants()->where('attendance_status', 'cancelled')->count(),
        'remaining_seats' => $event->max_participants - $event->participants()
            ->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])
            ->count(),
        'is_full' => $event->isFull(),
        'fill_percentage' => $event->fill_percentage
    ];

    $participantsData = $event->participants->map(function($participant) {
        return [
            'id' => $participant->id,
            'name' => $participant->user->name ?? 'Utilisateur inconnu',
            'email' => $participant->user->email ?? 'N/A',
            'phone' => $participant->user->phone ?? 'N/A',
            'registration_date' => $participant->registration_date->toISOString(),
            'status' => $participant->attendance_status,
            'rating' => $participant->rating,
            'feedback' => $participant->feedback,
        ];
    });

    return view('FrontOffice.Events.partials.participants-modal', compact(
        'event',
        'types',
        'stats',
        'participantsData'
    ));
}

/**
 * Afficher les événements de l'utilisateur
 * GET /mes-evenements
 */
/**
 * Afficher les événements de l'utilisateur
 * GET /mes-evenements
 */
public function myEvents(Request $request)
{
    $types = [
        'workshop' => ['label' => '🛠️ Workshop', 'icon' => 'fas fa-tools'],
        'collection' => ['label' => '🌱 Collection', 'icon' => 'fas fa-recycle'],
        'training' => ['label' => '📚 Formation', 'icon' => 'fas fa-graduation-cap'],
        'repair_cafe' => ['label' => '☕ Repair Café', 'icon' => 'fas fa-coffee']
    ];

    $userId = auth()->id(); // Temporaire pour les tests
    $tab = $request->get('tab', 'participating');

    // Événements auxquels je participe
    $participations = Event::with(['organizer', 'participants' => function($query) use ($userId) {
            $query->where('user_id', $userId);
        }])
        ->whereHas('participants', function($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('attendance_status', '!=', 'cancelled');
        })
        ->orderBy('date_start', 'desc')
        ->get();

    // Ajouter le compteur de participants actuels pour chaque événement
    $participations = $participations->map(function($event) {
        $event->current_participants = $event->participants()
            ->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])
            ->count();
        return $event;
    });

    // Événements que j'organise
    $organizingEvents = Event::where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get();

    // Ajouter le compteur de participants pour les événements organisés
    $organizingEvents = $organizingEvents->map(function($event) {
        $event->current_participants = $event->participants()
            ->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])
            ->count();
        return $event;
    });

    // Statistiques pour organisateur
    $organizerStats = [
        'total_events' => $organizingEvents->count(),
        'total_participants' => $organizingEvents->sum('current_participants'),
        'upcoming_events' => $organizingEvents->filter(function($event) {
            return $event->date_start > now();
        })->count(),
        'completed_events' => $organizingEvents->filter(function($event) {
            return $event->status === 'completed';
        })->count(),
        'average_attendance' => $this->calculateAverageAttendance($userId)
    ];

    // Renommer pour correspondre à la vue
    $organizedEvents = $organizingEvents;

    return view('FrontOffice.Events.mesevents', compact(
        'participations',
        'organizedEvents',
        'organizerStats',
        'types',
        'tab'
    ));
}

/**
 * Calculer le taux de présence moyen pour un organisateur
 */
private function calculateAverageAttendance($userId)
{
    $completedEvents = Event::where('user_id', $userId)
        ->where('status', 'completed')
        ->get();

    if ($completedEvents->isEmpty()) {
        return 0;
    }

    $totalAttendance = 0;
    $totalExpected = 0;

    foreach ($completedEvents as $event) {
        $attended = $event->participants()
            ->where('attendance_status', 'attended')
            ->count();
        
        $expected = $event->participants()
            ->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])
            ->count();

        $totalAttendance += $attended;
        $totalExpected += $expected;
    }

    return $totalExpected > 0 ? round(($totalAttendance / $totalExpected) * 100, 1) : 0;
}
    
    /**
     * Afficher le formulaire de création d'événement
     * GET /evenements/create
     */
 public function create(Request $request)
{
    $types = [
        'workshop' => [
            'label' => 'Workshop',
            'icon' => 'fas fa-tools'
        ],
        'collection' => [
            'label' => 'Collection',
            'icon' => 'fas fa-recycle'
        ],
        'training' => [
            'label' => 'Formation',
            'icon' => 'fas fa-graduation-cap'
        ],
        'repair_cafe' => [
            'label' => 'Repair Café',
            'icon' => 'fas fa-coffee'
        ]
    ];

    $cities = [
        'tunis', 'ariana', 'ben_arous', 'la_manouba', 'la_marsa',
        'sfax', 'sousse', 'kairouan', 'bizerte', 'gabes', 'monastir',
        'nabeul', 'hammamet', 'gafsa', 'medenine', 'kasserine'
    ];

    // Vérifier si on duplique un événement
    $duplicateEvent = null;
    if ($request->has('duplicate')) {
        $duplicateEvent = Event::find($request->get('duplicate'));
        
        // Vérifier que l'utilisateur est bien l'organisateur
        if ($duplicateEvent && $duplicateEvent->user_id !== 6) {
            $duplicateEvent = null; // Empêcher la duplication si pas le créateur
        }
    }

    return view('FrontOffice.Events.create', compact('types', 'cities', 'duplicateEvent'));
}

    /**
     * Enregistrer un nouvel événement
     * POST /evenements
     */
    public function store(EventRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id(); // Utilisateur actuellement connecté
        
        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        // Définir le statut
        $data['status'] = $request->has('publish_now') ? 'published' : 'draft';

        $event = Event::create($data);

        $message = $data['status'] === 'published' 
            ? 'Événement créé et publié avec succès !' 
            : 'Événement sauvegardé en brouillon.';

        return redirect()
            ->route('Events.show', $event->id)
            ->with('success', $message);
    }

    /**
     * Afficher le formulaire d'édition
     * GET /evenements/{id}/edit
     */
    public function edit($id)
    {
        $event = Event::with('participants')->findOrFail($id);

        // Vérifier que l'utilisateur est l'organisateur
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cet événement.');
        }

       $types = [
        'workshop' => [
            'label' => 'Workshop',
            'icon' => 'fas fa-tools'
        ],
        'collection' => [
            'label' => 'Collection',
            'icon' => 'fas fa-recycle'
        ],
        'training' => [
            'label' => 'Formation',
            'icon' => 'fas fa-graduation-cap'
        ],
        'repair_cafe' => [
            'label' => 'Repair Café',
            'icon' => 'fas fa-coffee'
        ]
    ];

        $cities = [
            'tunis', 'ariana', 'ben_arous', 'la_manouba', 'la_marsa',
            'sfax', 'sousse', 'kairouan', 'bizerte', 'gabes', 'monastir',
            'nabeul', 'hammamet', 'gafsa', 'medenine', 'kasserine'
        ];

        $hasParticipants = $event->participants()
            ->where('attendance_status', '!=', 'cancelled')
            ->exists();

        return view('FrontOffice.Events.edit', compact('event', 'types', 'cities', 'hasParticipants'));
    }

    /**
     * Mettre à jour un événement
     * PUT/PATCH /evenements/{id}
     */
    public function update(EventRequest $request, $id)
    {
        $event = Event::findOrFail($id);

        // Vérifier que l'utilisateur est l'organisateur
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cet événement.');
        }

        $data = $request->validated();

        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);

        // Envoyer une notification aux participants si demandé
        if ($request->boolean('notify_participants')) {
            $this->notifyParticipantsOfUpdate($event);
        }

        return redirect()
            ->route('Events.show', $event->id)
            ->with('success', 'Événement mis à jour avec succès !');
    }

    /**
     * Supprimer un événement
     * DELETE /evenements/{id}
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // Vérifier que l'utilisateur est l'organisateur
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cet événement.');
        }

        // Supprimer l'image associée
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()
            ->route('Events.index')
            ->with('success', 'Événement supprimé avec succès.');
    }

    /**
     * Inscription à un événement
     * POST /evenements/{id}/register
     */
    public function register(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Vérifications
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour vous inscrire.');
        }

        // Vérifier si déjà inscrit
        $existingParticipation = Participant::where('event_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingParticipation && $existingParticipation->attendance_status !== 'cancelled') {
            return redirect()->back()
                ->with('warning', 'Vous êtes déjà inscrit à cet événement.');
        }

        // Vérifier si l'événement est complet
        $currentParticipants = $event->participants()
            ->where('attendance_status', '!=', 'cancelled')
            ->count();

        if ($currentParticipants >= $event->max_participants) {
            return redirect()->back()
                ->with('error', 'Désolé, cet événement est complet.');
        }

        // Créer ou réactiver la participation
        if ($existingParticipation) {
            $existingParticipation->update([
                'attendance_status' => 'registered',
                'registration_date' => Carbon::now(),
                'email_sent' => false
            ]);
        } else {
            Participant::create([
                'event_id' => $id,
                'user_id' => Auth::id(),
                'registration_date' => Carbon::now(),
                'attendance_status' => 'registered',
                'email_sent' => false
            ]);
        }

        // TODO: Envoyer email de confirmation
        // event(new ParticipantRegistered($event, Auth::user()));

        return redirect()->back()
            ->with('success', 'Inscription réussie ! Vous recevrez un email de confirmation.');
    }

    /**
     * Annuler l'inscription à un événement
     * POST /evenements/{id}/unregister
     */
    public function unregister($id)
    {
        $participation = Participant::where('event_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $event = Event::findOrFail($id);

        // Vérifier si l'événement n'a pas déjà commencé
        if (Carbon::now()->greaterThan($event->date_start)) {
            return redirect()->back()
                ->with('error', 'Impossible de se désinscrire d\'un événement déjà commencé.');
        }

        $participation->update([
            'attendance_status' => 'cancelled'
        ]);

        return redirect()->back()
            ->with('success', 'Votre inscription a été annulée.');
    }

    /**
     * Afficher les événements de l'utilisateur
     * GET /mes-evenements
     */
    

    /**
     * Afficher les participants d'un événement
     * GET /evenements/{id}/participants
     */
    public function participants(Request $request, $id)
    {
        $event = Event::with('organizer')->findOrFail($id);

        // Vérifier que l'utilisateur est l'organisateur
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir cette page.');
        }

        // Récupérer les participants
        $participantsQuery = Participant::where('event_id', $id)
            ->with('user');

        // Filtre par statut
        if ($request->filled('status')) {
            $participantsQuery->where('attendance_status', $request->status);
        }

        // Recherche par nom
        if ($request->filled('search')) {
            $participantsQuery->whereHas('user', function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $participants = $participantsQuery->orderBy('registration_date', 'desc')->get();

        // Statistiques
        $stats = [
            'total' => $participants->count(),
            'registered' => $participants->where('attendance_status', 'registered')->count(),
            'confirmed' => $participants->where('attendance_status', 'confirmed')->count(),
            'attended' => $participants->where('attendance_status', 'attended')->count(),
            'cancelled' => $participants->where('attendance_status', 'cancelled')->count()
        ];

        return view('FrontOffice.Events.partials.participants-modal', compact('event', 'participants', 'stats'));
    }

    /**
     * Annuler un événement
     * POST /evenements/{id}/cancel
     */
    public function cancel($id)
    {
        $event = Event::findOrFail($id);

        // Vérifier que l'utilisateur est l'organisateur
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à annuler cet événement.');
        }

        $event->update(['status' => 'cancelled']);

        // Notifier tous les participants
        $this->notifyParticipantsOfCancellation($event);

        return redirect()
            ->route('mes-evenements')
            ->with('success', 'Événement annulé. Les participants ont été notifiés.');
    }

    /**
     * Dupliquer un événement
     * POST /evenements/{id}/duplicate
     */
    public function duplicate($id)
    {
        $event = Event::findOrFail($id);

        // Vérifier que l'utilisateur est l'organisateur
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $newEvent = $event->replicate();
        $newEvent->title = $event->title . ' (Copie)';
        $newEvent->status = 'draft';
        $newEvent->date_start = Carbon::now()->addWeek();
        $newEvent->date_end = Carbon::now()->addWeek()->addHours(3);
        $newEvent->save();

        return redirect()
            ->route('Events.edit', $newEvent->id)
            ->with('success', 'Événement dupliqué. Modifiez les détails avant de publier.');
    }

    /**
     * Laisser un avis sur un événement
     * POST /evenements/{id}/review
     */
    public function review(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'required|string|min:10|max:1000'
        ]);

        $participation = Participant::where('event_id', $id)
            ->where('user_id', Auth::id())
            ->where('attendance_status', 'attended')
            ->firstOrFail();

        $participation->update([
            'rating' => $request->rating,
            'feedback' => $request->feedback
        ]);

        return redirect()->back()
            ->with('success', 'Merci pour votre avis !');
    }

    /**
     * Exporter la liste des participants en CSV
     * GET /evenements/{id}/participants/export
     */
    public function exportParticipants($id)
    {
        $event = Event::findOrFail($id);

        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $participants = Participant::where('event_id', $id)
            ->with('user')
            ->get();

        $filename = Str::slug($event->title) . '-participants-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ];

        $callback = function() use ($participants) {
            $file = fopen('php://output', 'w');
            
            // En-têtes
            fputcsv($file, ['Nom', 'Email', 'Date inscription', 'Statut']);

            // Données
            foreach ($participants as $participant) {
                fputcsv($file, [
                    $participant->user->name,
                    $participant->user->email,
                    $participant->registration_date->format('d/m/Y H:i'),
                    $participant->attendance_status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Calculer le temps restant jusqu'à l'événement
     */
    private function calculateTimeUntil($dateStart)
    {
        $now = Carbon::now();
        $start = Carbon::parse($dateStart);

        if ($now->greaterThan($start)) {
            return ['started' => true];
        }

        $diff = $now->diff($start);

        return [
            'started' => false,
            'days' => $diff->d,
            'hours' => $diff->h,
            'minutes' => $diff->i
        ];
    }

    /**
     * Calculer le taux de présence moyen
     */
    

    /**
     * Notifier les participants d'une mise à jour
     */
    private function notifyParticipantsOfUpdate($event)
    {
        // TODO: Implémenter l'envoi d'emails
        // $participants = $event->participants()->where('attendance_status', '!=', 'cancelled')->get();
        // foreach ($participants as $participant) {
        //     Mail::to($participant->user->email)->send(new EventUpdated($event));
        // }
    }

    /**
     * Notifier les participants d'une annulation
     */
    private function notifyParticipantsOfCancellation($event)
    {
        // TODO: Implémenter l'envoi d'emails
        // $participants = $event->participants()->where('attendance_status', '!=', 'cancelled')->get();
        // foreach ($participants as $participant) {
        //     Mail::to($participant->user->email)->send(new EventCancelled($event));
        // }
    }

    
 
// Add these methods to your EventController class

/**
 * Update a single participant's status
 * PATCH /evenements/{eventId}/participants/{participantId}/status
 */
public function updateParticipantStatus(Request $request, $eventId, $participantId)
{
    $request->validate([
        'attendance_status' => 'required|in:registered,confirmed,attended,cancelled'
    ]);

    $event = Event::findOrFail($eventId);

    // Verify the user is the organizer
    if ($event->user_id !== auth()->id()) {
        return response()->json([
            'success' => false,
            'message' => 'Non autorisé'
        ], 403);
    }

    // Find participant and verify it belongs to this event
    $participant = Participant::where('id', $participantId)
        ->where('event_id', $eventId)
        ->first();
    
    if (!$participant) {
        return response()->json([
            'success' => false,
            'message' => 'Participant non trouvé pour cet événement'
        ], 404);
    }

    $oldStatus = $participant->attendance_status;
    $participant->attendance_status = $request->attendance_status;
    $participant->save();

    // Optional: Send notification email
    // if ($request->attendance_status === 'confirmed') {
    //     Mail::to($participant->user->email)->send(new ParticipationConfirmed($event, $participant));
    // }

    return response()->json([
        'success' => true,
        'message' => 'Statut mis à jour avec succès',
        'data' => [
            'participant_id' => $participant->id,
            'old_status' => $oldStatus,
            'new_status' => $participant->attendance_status
        ]
    ]);
}

/**
 * Bulk update participants' status
 * POST /evenements/{eventId}/participants/bulk-status
 */
public function bulkUpdateParticipantStatus(Request $request, $eventId)
{
    \Log::info('Bulk update status request', [
        'event_id' => $eventId,
        'participant_ids' => $request->input('participant_ids'),
        'new_status' => $request->input('attendance_status')
    ]);

    $request->validate([
        'participant_ids' => 'required|array|min:1',
        'participant_ids.*' => 'integer|exists:participants,id',
        'attendance_status' => 'required|in:registered,confirmed,attended,cancelled'
    ]);

    $event = Event::findOrFail($eventId);

    // Verify the user is the organizer
    if ($event->user_id !== auth()->id()) {
        return response()->json([
            'success' => false,
            'message' => 'Non autorisé'
        ], 403);
    }

    // Verify all participants belong to this event
    $validParticipants = Participant::where('event_id', $eventId)
        ->whereIn('id', $request->participant_ids)
        ->pluck('id')
        ->toArray();
    
    if (count($validParticipants) !== count($request->participant_ids)) {
        $invalidIds = array_diff($request->participant_ids, $validParticipants);
        return response()->json([
            'success' => false,
            'message' => 'Certains participants ne font pas partie de cet événement (IDs: ' . implode(', ', $invalidIds) . ')'
        ], 400);
    }

    $updatedCount = Participant::where('event_id', $eventId)
        ->whereIn('id', $validParticipants)
        ->update(['attendance_status' => $request->attendance_status]);

    // Optional: Send bulk notification emails
    // if ($request->attendance_status === 'confirmed') {
    //     $participants = Participant::where('event_id', $eventId)
    //         ->whereIn('id', $request->participant_ids)
    //         ->with('user')
    //         ->get();
    //     
    //     foreach ($participants as $participant) {
    //         Mail::to($participant->user->email)->send(new ParticipationConfirmed($event, $participant));
    //     }
    // }

    return response()->json([
        'success' => true,
        'message' => "{$updatedCount} participant(s) mis à jour",
        'data' => [
            'updated_count' => $updatedCount,
            'new_status' => $request->attendance_status
        ]
    ]);
}

/**
 * Delete a participant from an event
 * DELETE /evenements/{eventId}/participants/{participantId}
 */
public function deleteParticipant($eventId, $participantId)
{
    try {
        $event = Event::findOrFail($eventId);

        // Verify the user is the organizer
        if ($event->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        // Find participant and verify it belongs to this event
        $participant = Participant::where('id', $participantId)
            ->where('event_id', $eventId)
            ->with('user')
            ->first();
        
        if (!$participant) {
            return response()->json([
                'success' => false,
                'message' => 'Participant non trouvé pour cet événement'
            ], 404);
        }

        $participantName = $participant->user->name ?? 'Participant';
        
        // Optional: Send cancellation email
        // Mail::to($participant->user->email)->send(new ParticipationCancelled($event));

        $participant->delete();

        return response()->json([
            'success' => true,
            'message' => "{$participantName} a été supprimé de l'événement",
            'data' => [
                'participant_id' => $participantId,
                'deleted' => true,
                'remaining_participants' => $event->participants()
                    ->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])
                    ->count()
            ]
        ], 200);
        
    } catch (\Exception $e) {
        \Log::error('Error deleting participant: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la suppression du participant'
        ], 500);
    }
}

/**
 * Bulk delete participants (FIXED)
 * POST /evenements/{eventId}/participants/bulk-delete
 */
public function bulkDeleteParticipants(Request $request, $eventId)
{
    try {
        // Log the incoming request for debugging
        \Log::info('Bulk delete request', [
            'event_id' => $eventId,
            'participant_ids' => $request->input('participant_ids'),
            'user_id' => auth()->id()
        ]);

        $request->validate([
            'participant_ids' => 'required|array|min:1',
            'participant_ids.*' => 'integer|exists:participants,id'
        ]);

        $event = Event::findOrFail($eventId);

        // Verify the user is the organizer
        if ($event->user_id !== auth()->id()) {
            \Log::warning('Unauthorized bulk delete attempt', [
                'event_id' => $eventId,
                'event_owner' => $event->user_id,
                'user_id' => auth()->id()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        // Verify all participants belong to this event
        $validParticipants = Participant::where('event_id', $eventId)
            ->whereIn('id', $request->participant_ids)
            ->pluck('id')
            ->toArray();
        
        \Log::info('Participant validation', [
            'requested_ids' => $request->participant_ids,
            'valid_ids' => $validParticipants,
            'event_id' => $eventId
        ]);
        
        if (count($validParticipants) !== count($request->participant_ids)) {
            $invalidIds = array_diff($request->participant_ids, $validParticipants);
            \Log::warning('Invalid participants in bulk delete', [
                'event_id' => $eventId,
                'invalid_ids' => $invalidIds
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Certains participants ne font pas partie de cet événement (IDs: ' . implode(', ', $invalidIds) . ')'
            ], 400);
        }

        // Optional: Get participants for notification before deletion
        // $participants = Participant::where('event_id', $eventId)
        //     ->whereIn('id', $request->participant_ids)
        //     ->with('user')
        //     ->get();
        // 
        // foreach ($participants as $participant) {
        //     Mail::to($participant->user->email)->send(new ParticipationCancelled($event));
        // }

        $deletedCount = Participant::where('event_id', $eventId)
            ->whereIn('id', $validParticipants)
            ->delete();

        \Log::info('Participants deleted successfully', [
            'event_id' => $eventId,
            'deleted_count' => $deletedCount
        ]);

        return response()->json([
            'success' => true,
            'message' => "{$deletedCount} participant(s) supprimé(s)",
            'data' => [
                'deleted_count' => $deletedCount,
                'remaining_participants' => $event->participants()
                    ->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])
                    ->count()
            ]
        ], 200);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation error in bulk delete', [
            'errors' => $e->errors(),
            'event_id' => $eventId
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Données invalides',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        \Log::error('Error bulk deleting participants', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'event_id' => $eventId
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la suppression des participants'
        ], 500);
    }
}

/**
 * Send bulk email to participants
 * POST /evenements/{eventId}/participants/send-email
 */
public function sendBulkEmail(Request $request, $eventId)
{
    try {
        \Log::info('Send bulk email request received', [
            'event_id' => $eventId,
            'participant_ids' => $request->input('participant_ids'),
            'subject' => $request->input('subject')
        ]);

        // Validate request
        $request->validate([
            'participant_ids' => 'required|array|min:1',
            'participant_ids.*' => 'integer|exists:participants,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000'
        ]);

        $event = Event::findOrFail($eventId);

        // Verify the user is the organizer
        if ($event->user_id !== auth()->id()) {
            \Log::warning('Unauthorized email send attempt');
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        // Get participants with their user information
        $participants = Participant::where('event_id', $eventId)
            ->whereIn('id', $request->participant_ids)
            ->with('user')
            ->get();

        \Log::info('Participants found for email', [
            'count' => $participants->count()
        ]);

        if ($participants->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun participant trouvé'
            ], 404);
        }

        // Send emails to each participant
        $emailsSent = 0;
        $failedEmails = [];

        foreach ($participants as $participant) {
            \Log::info('Processing participant for email', [
                'participant_id' => $participant->id,
                'has_user' => $participant->user !== null,
                'has_email' => $participant->user ? ($participant->user->email !== null) : false
            ]);

            if ($participant->user && $participant->user->email) {
                try {
                    Mail::to($participant->user->email)->send(
                        new ParticipantNotification(
                            $event,
                            $participant->user,
                            $request->subject,
                            $request->message
                        )
                    );
                    $emailsSent++;
                    
                    \Log::info('Email sent successfully', [
                        'participant_id' => $participant->id,
                        'email' => $participant->user->email
                    ]);
                } catch (\Exception $e) {
                    $failedEmails[] = $participant->user->email;
                    \Log::error('Failed to send individual email', [
                        'participant_id' => $participant->id,
                        'error' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]);
                }
            } else {
                $failedEmails[] = "Participant #{$participant->id} (pas d'email)";
            }
        }

        if ($emailsSent === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun email n\'a pu être envoyé',
                'data' => [
                    'failed_emails' => $failedEmails
                ]
            ], 500);
        }

        $message = $emailsSent === count($participants)
            ? "Tous les emails ont été envoyés avec succès ({$emailsSent})"
            : "{$emailsSent} email(s) envoyé(s) sur " . count($participants);

        if (!empty($failedEmails)) {
            $message .= ". Échec: " . implode(', ', $failedEmails);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'emails_sent' => $emailsSent,
                'total_participants' => count($participants),
                'failed_emails' => $failedEmails
            ]
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Données invalides',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        \Log::error('Error sending bulk emails', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'event_id' => $eventId,
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'envoi des emails: ' . $e->getMessage()
        ], 500);
    }
}


}
