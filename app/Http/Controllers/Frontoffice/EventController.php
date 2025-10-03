<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use App\Http\Requests\EventRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
public function show($id)
{
    $types = [
        'workshop' => ['label' => '🛠️ Workshop', 'icon' => 'fas fa-tools'],
        'collection' => ['label' => '🌱 Collection', 'icon' => 'fas fa-recycle'],
        'training' => ['label' => '📚 Formation', 'icon' => 'fas fa-graduation-cap'],
        'repair_cafe' => ['label' => '☕ Repair Café', 'icon' => 'fas fa-coffee']
    ];

    // Récupérer l'événement avec ses relations
    $event = Event::with(['organizer', 'participants.user'])
        ->findOrFail($id);

    // Vérifier si l'utilisateur (ID = 6 pour les tests) est déjà inscrit
    $userId = 6; // Temporaire pour les tests
    $isParticipant = $event->participants()
        ->where('user_id', $userId)
        ->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])
        ->exists();

    // Récupérer la participation de l'utilisateur si elle existe
    $userParticipation = $event->participants()
        ->where('user_id', $userId)
        ->first();

    // Calculer les statistiques de l'événement
    $stats = [
        'current_participants' => $event->participants()
            ->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])
            ->count(),
        'is_full' => $event->isFull(),
        'remaining_seats' => $event->remaining_seats,
        'fill_percentage' => $event->fill_percentage
    ];

    // Événements similaires (même type, excluant l'événement actuel)
    $similarEvents = Event::with(['organizer'])
        ->where('type', $event->type)
        ->where('id', '!=', $id)
        ->where('status', 'published')
        ->where('date_start', '>', now())
        ->take(3)
        ->get();

    // Vérifier si l'utilisateur est l'organisateur
    $isOrganizer = $event->user_id == $userId;

    return view('FrontOffice.Events.show', compact(
        'event',
        'types',
        'isParticipant',
        'userParticipation',
        'stats',
        'similarEvents',
        'isOrganizer'
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

    $userId = 6; // Temporaire pour les tests
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
        $data['user_id'] = 6;
        
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
            'workshop' => 'Workshop',
            'collection' => 'Collection',
            'training' => 'Formation',
            'repair_cafe' => 'Repair Café'
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
            ->route('evenements.show', $event->id)
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
            ->route('evenements.index')
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

        return view('FrontOffice.Events.participants', compact('event', 'participants', 'stats'));
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
            ->route('evenements.edit', $newEvent->id)
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
}