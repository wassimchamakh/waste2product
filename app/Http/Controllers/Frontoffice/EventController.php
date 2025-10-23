<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use App\Http\Requests\EventRequest;
use App\Mail\ParticipantNotification;
use App\Services\SentimentAnalysisService;
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

    // Check for pending refund request
    $pendingRefundRequest = null;
    if ($userParticipation) {
        $pendingRefundRequest = \App\Models\RefundRequest::where('participant_id', $userParticipation->id)
            ->whereIn('status', ['pending', 'processing'])
            ->first();
    }

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

    // Payment statistics (only for paid events)
    $paymentStats = null;
    if ($event->isPaid()) {
        $paymentStats = (object) [
            'total_revenue' => $event->participants()
                ->where('payment_status', 'completed')
                ->sum('amount_paid'),
            'paid_participants' => $event->participants()
                ->where('payment_status', 'completed')
                ->count(),
            'pending_payments' => $event->participants()
                ->where('payment_status', 'pending_payment')
                ->count(),
            'failed_payments' => $event->participants()
                ->where('payment_status', 'failed')
                ->count(),
            'refunded' => $event->participants()
                ->whereIn('payment_status', ['refunded', 'partially_refunded'])
                ->count(),
            'total_refunded' => $event->participants()
                ->whereIn('payment_status', ['refunded', 'partially_refunded'])
                ->sum('amount_refunded'),
        ];
    }

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
            'payment_status' => $participant->payment_status,
            'amount_paid' => $participant->amount_paid,
            'invoice_number' => $participant->invoice_number,
            'payment_completed_at' => $participant->payment_completed_at ? $participant->payment_completed_at->toISOString() : null,
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
        'participantsData',
        'paymentStats',
        'pendingRefundRequest'
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
            'payment_status' => $participant->payment_status,
            'amount_paid' => $participant->amount_paid,
            'invoice_number' => $participant->invoice_number,
            'payment_completed_at' => $participant->payment_completed_at ? $participant->payment_completed_at->toISOString() : null,
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

        // Handle checkboxes (they won't be in request if unchecked)
        $data['parking_available'] = $request->has('parking_available');
        $data['accessible_pmr'] = $request->has('accessible_pmr');
        $data['wifi_available'] = $request->has('wifi_available');

        // Handle program JSON
        if ($request->has('program') && !empty($request->program)) {
            $data['program'] = json_decode($request->program, true);
        }

        // Handle coordinates (ensure they're numbers or null)
        $data['latitude'] = $request->latitude ? (float)$request->latitude : null;
        $data['longitude'] = $request->longitude ? (float)$request->longitude : null;

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

        // Handle checkboxes
        $data['parking_available'] = $request->has('parking_available');
        $data['accessible_pmr'] = $request->has('accessible_pmr');
        $data['wifi_available'] = $request->has('wifi_available');

        // Handle program JSON
        if ($request->has('program') && !empty($request->program)) {
            $data['program'] = json_decode($request->program, true);
        }

        // Handle coordinates
        $data['latitude'] = $request->latitude ? (float)$request->latitude : null;
        $data['longitude'] = $request->longitude ? (float)$request->longitude : null;

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
     * Inscription à un événement (Enhanced for Paid Events)
     * POST /evenements/{id}/register
     */
    public function register(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // 1. Authentication check
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour vous inscrire.');
        }

        // 2. Check if already registered (active registration only)
        $existingParticipation = Participant::where('event_id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])
            ->whereNotIn('payment_status', ['failed', 'refunded', 'expired'])
            ->first();

        if ($existingParticipation) {
            // If payment is pending, redirect to payment page
            if ($existingParticipation->payment_status === 'pending_payment') {
                return redirect()->route('Events.payment', [
                    'event' => $event->id,
                    'participant' => $existingParticipation->id
                ])->with('info', 'Veuillez compléter votre paiement.');
            }

            return redirect()->back()
                ->with('warning', 'Vous êtes déjà inscrit à cet événement.');
        }

        // 3. Delete any cancelled/failed previous registrations to avoid unique constraint violation
        Participant::where('event_id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('attendance_status', ['cancelled'])
            ->orWhere(function($query) use ($id) {
                $query->where('event_id', $id)
                      ->where('user_id', Auth::id())
                      ->whereIn('payment_status', ['failed', 'expired']);
            })
            ->delete();

        // 3. Check capacity
        $currentParticipants = $event->participants()
            ->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])
            ->count();

        if ($currentParticipants >= $event->max_participants) {
            return redirect()->back()
                ->with('error', 'Désolé, cet événement est complet.');
        }

        // 4. Check if event hasn't started
        if (Carbon::now()->gte($event->date_start)) {
            return redirect()->back()
                ->with('error', 'Cet événement a déjà commencé.');
        }

        // 5. Get event price (considering early bird)
        $price = $event->getCurrentPrice();

        // 6. Create participant
        $participant = Participant::create([
            'event_id' => $id,
            'user_id' => Auth::id(),
            'registration_date' => Carbon::now(),
            'attendance_status' => 'registered',
            'payment_status' => $price > 0 ? 'pending_payment' : 'not_required',
            'amount_paid' => null,
            'email_sent' => false
        ]);

        // 7. Handle based on event type
        if ($price > 0) {
            // PAID EVENT - Redirect to payment page
            return redirect()->route('Events.payment', [
                'event' => $event->id,
                'participant' => $participant->id
            ])->with('info', 'Veuillez procéder au paiement pour confirmer votre inscription.');

        } else {
            // FREE EVENT - Immediate confirmation
            $participant->update([
                'attendance_status' => 'confirmed',
                'payment_status' => 'not_required'
            ]);

            // TODO: Send confirmation email
            // dispatch(new SendRegistrationConfirmation($participant));

            return redirect()->back()
                ->with('success', 'Inscription confirmée avec succès!');
        }
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
     * Submit feedback for an event (with automatic sentiment analysis)
     * POST /events/{event}/feedback
     */
    public function submitFeedback(Request $request, $id)
    {
        // Validate input
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'required|string|min:10|max:1000'
        ]);

        // Check if user attended this event
        $participation = Participant::where('event_id', $id)
            ->where('user_id', Auth::id())
            ->where('attendance_status', 'attended')
            ->firstOrFail();

        // Check if feedback already exists
        if ($participation->feedback) {
            return redirect()->back()
                ->with('warning', 'Vous avez déjà laissé un avis pour cet événement.');
        }

        // Update participation with feedback and rating
        $participation->update([
            'rating' => $validated['rating'],
            'feedback' => $validated['feedback']
        ]);

        // Automatically analyze sentiment
        try {
            $sentimentService = app(SentimentAnalysisService::class);
            
            if ($sentimentService->isAvailable()) {
                $result = $sentimentService->analyze($validated['feedback']);
                
                if ($result && isset($result['sentiment'])) {
                    $sentiment = $result['sentiment'];
                    
                    $participation->update([
                        'sentiment_label' => $sentiment['label'] ?? null,
                        'sentiment_score' => $sentiment['score'] ?? null,
                        'sentiment_confidence' => $sentiment['confidence'] ?? null,
                        'sentiment_details' => json_encode($sentiment),
                        'feedback_themes' => json_encode($result['themes'] ?? []),
                        'sentiment_analyzed_at' => now()
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Sentiment analysis failed, but feedback is saved
            \Log::warning('Sentiment analysis failed for feedback: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', 'Merci pour votre avis ! Il a été enregistré avec succès.');
    }

    /**
     * Laisser un avis sur un événement (Legacy method - kept for compatibility)
     * POST /evenements/{id}/review
     */
    public function review(Request $request, $id)
    {
        return $this->submitFeedback($request, $id);
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

/**
 * Analyze sentiment for all event feedback
 * POST /evenements/{eventId}/analyze-sentiment
 */
public function analyzeSentiment($eventId)
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

        // Get all feedback that hasn't been analyzed or needs re-analysis
        $participants = Participant::where('event_id', $eventId)
            ->whereNotNull('feedback')
            ->where('feedback', '!=', '')
            ->get();

        if ($participants->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun feedback à analyser'
            ], 404);
        }

        $sentimentService = new SentimentAnalysisService();

        // Check if API is available
        if (!$sentimentService->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'Le service d\'analyse de sentiment n\'est pas disponible. Assurez-vous que le service Python est en cours d\'exécution.'
            ], 503);
        }

        // Prepare batch data
        $feedbackList = $participants->map(function($participant) {
            return [
                'id' => $participant->id,
                'text' => $participant->feedback
            ];
        })->toArray();

        // Analyze in batch
        $results = $sentimentService->analyzeBatch($feedbackList);

        if (!$results) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'analyse'
            ], 500);
        }

        // Update participants with sentiment data
        $updatedCount = 0;
        foreach ($results['results'] as $result) {
            $participant = Participant::find($result['id']);
            if ($participant && isset($result['sentiment'])) {
                $participant->update([
                    'sentiment_label' => $result['sentiment']['label'],
                    'sentiment_score' => $result['sentiment']['score'],
                    'sentiment_confidence' => $result['sentiment']['confidence'],
                    'sentiment_details' => json_encode($result['sentiment']),
                    'feedback_themes' => json_encode($result['themes']),
                    'sentiment_analyzed_at' => now()
                ]);
                $updatedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$updatedCount} feedback analysé(s) avec succès",
            'data' => [
                'analyzed_count' => $updatedCount,
                'aggregate' => $results['aggregate'],
                'top_themes' => $results['top_themes']
            ]
        ]);

    } catch (\Exception $e) {
        \Log::error('Error analyzing sentiment', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'analyse: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Get sentiment analysis results for an event
 * GET /evenements/{eventId}/sentiment-results
 */
public function getSentimentResults($eventId)
{
    try {
        $event = Event::with(['participants' => function($query) {
            $query->whereNotNull('feedback')
                  ->whereNotNull('sentiment_label');
        }])->findOrFail($eventId);

        // Verify the user is the organizer
        if ($event->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        $participants = $event->participants;

        if ($participants->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune analyse disponible'
            ], 404);
        }

        // Calculate aggregate statistics
        $totalCount = $participants->count();
        $positiveCount = $participants->where('sentiment_label', 'positive')->count();
        $negativeCount = $participants->where('sentiment_label', 'negative')->count();
        $neutralCount = $participants->where('sentiment_label', 'neutral')->count();

        $avgScore = $participants->avg('sentiment_score');
        $avgConfidence = $participants->avg('sentiment_confidence');

        // Get all themes
        $allThemes = [];
        foreach ($participants as $participant) {
            if ($participant->feedback_themes) {
                $themes = json_decode($participant->feedback_themes, true);
                if (is_array($themes)) {
                    $allThemes = array_merge($allThemes, $themes);
                }
            }
        }
        $themeCounts = array_count_values($allThemes);
        arsort($themeCounts);
        $topThemes = array_slice($themeCounts, 0, 10, true);

        // Get concerning feedback (negative sentiment)
        $concerns = $participants
            ->where('sentiment_label', 'negative')
            ->map(function($p) {
                return [
                    'participant' => $p->user->name ?? 'Anonyme',
                    'feedback' => $p->feedback,
                    'score' => $p->sentiment_score,
                    'themes' => json_decode($p->feedback_themes, true) ?? []
                ];
            })
            ->values();

        $aggregate = [
            'total_count' => $totalCount,
            'positive_count' => $positiveCount,
            'negative_count' => $negativeCount,
            'neutral_count' => $neutralCount,
            'positive_percentage' => $totalCount > 0 ? round(($positiveCount / $totalCount) * 100, 2) : 0,
            'negative_percentage' => $totalCount > 0 ? round(($negativeCount / $totalCount) * 100, 2) : 0,
            'neutral_percentage' => $totalCount > 0 ? round(($neutralCount / $totalCount) * 100, 2) : 0,
            'average_score' => round($avgScore, 4),
            'average_confidence' => round($avgConfidence, 4),
            'last_analyzed' => $participants->max('sentiment_analyzed_at')
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'aggregate' => $aggregate,
                'top_themes' => $topThemes,
                'concerns' => $concerns,
                'feedback_list' => $participants->map(function($p) {
                    return [
                        'id' => $p->id,
                        'user' => $p->user->name ?? 'Anonyme',
                        'feedback' => $p->feedback,
                        'rating' => $p->rating,
                        'sentiment_label' => $p->sentiment_label,
                        'sentiment_score' => $p->sentiment_score,
                        'sentiment_confidence' => $p->sentiment_confidence,
                        'themes' => json_decode($p->feedback_themes, true) ?? [],
                        'analyzed_at' => $p->sentiment_analyzed_at
                    ];
                })
            ]
        ]);

    } catch (\Exception $e) {
        \Log::error('Error getting sentiment results', [
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la récupération des résultats'
        ], 500);
    }
}

/**
 * View participant certificate
 */
public function viewCertificate($eventId, $participantId)
{
    $participant = Participant::with(['event.user', 'user'])->findOrFail($participantId);
    $event = $participant->event;

    // Check authorization - participant themselves or event organizer
    if (Auth::id() !== $participant->user_id && Auth::id() !== $event->user_id) {
        abort(403, 'Non autorisé');
    }

    // Check eligibility
    if (!\App\Helpers\CertificateHelper::isEligibleForCertificate($participant)) {
        return redirect()->back()->with('error', 'Ce participant n\'est pas éligible pour un certificat.');
    }

    // Generate certificate
    $certificateHTML = \App\Helpers\CertificateHelper::generateCertificate($participant);

    return response($certificateHTML)->header('Content-Type', 'text/html');
}

/**
 * Send certificates to all attended participants
 */
public function sendCertificates(Request $request, $eventId)
{
    $event = Event::findOrFail($eventId);

    // Check authorization - only event organizer
    if (Auth::id() !== $event->user_id) {
        return redirect()->back()->with('error', 'Non autorisé');
    }

    // Get all attended participants
    $attendedParticipants = $event->participants()
        ->where('attendance_status', 'attended')
        ->with('user')
        ->get();

    if ($attendedParticipants->isEmpty()) {
        return redirect()->back()->with('error', 'Aucun participant présent trouvé.');
    }

    $sentCount = 0;
    foreach ($attendedParticipants as $participant) {
        if (\App\Helpers\CertificateHelper::isEligibleForCertificate($participant)) {
            try {
                // Send email with certificate
                Mail::to($participant->user->email)->send(
                    new \App\Mail\CertificateNotification($participant)
                );
                $sentCount++;
            } catch (\Exception $e) {
                \Log::error('Error sending certificate: ' . $e->getMessage());
            }
        }
    }

    return redirect()->back()->with('success', "Certificats envoyés à {$sentCount} participant(s).");
}

/**
 * View participant ticket
 */
public function viewTicket($eventId, $participantId)
{
    $participant = Participant::with(['event.user', 'user'])->findOrFail($participantId);
    $event = $participant->event;

    // Check authorization - participant themselves or event organizer
    if (Auth::id() !== $participant->user_id && Auth::id() !== $event->user_id) {
        abort(403, 'Non autorisé');
    }

    // Check eligibility
    if (!\App\Helpers\TicketHelper::canViewTicket($participant)) {
        return redirect()->back()->with('error', 'Votre billet n\'est pas encore disponible. Veuillez confirmer votre participation ou compléter le paiement.');
    }

    // Generate ticket data
    $ticketNumber = \App\Helpers\TicketHelper::generateTicketNumber($participant);
    $qrCodeSVG = \App\Helpers\TicketHelper::generateQRCodeSVG($participant);
    $statusBadge = \App\Helpers\TicketHelper::getTicketStatusBadge($participant);

    return view('tickets.ticket-view', compact('participant', 'event', 'ticketNumber', 'qrCodeSVG', 'statusBadge'));
}

/**
 * Verify ticket via QR code scan
 */
public function verifyTicket($eventId, $ticketNumber)
{
    $event = Event::findOrFail($eventId);

    // Check authorization - only event organizer can verify tickets
    // Also log the check for debugging
    \Log::info('Ticket verification attempt', [
        'event_id' => $eventId,
        'event_user_id' => $event->user_id,
        'current_user_id' => Auth::id(),
        'is_authenticated' => Auth::check(),
        'ticket_number' => $ticketNumber
    ]);

    if (!Auth::check()) {
        return response()->json([
            'success' => false,
            'message' => 'Vous devez être connecté pour vérifier les billets'
        ], 401);
    }

    if (Auth::id() !== $event->user_id) {
        return response()->json([
            'success' => false,
            'message' => 'Seul l\'organisateur de cet événement peut vérifier les billets'
        ], 403);
    }

    // Find participant by ticket number pattern: TKT-YEAR-EVENTID-PARTICIPANTID
    $parts = explode('-', $ticketNumber);
    
    if (count($parts) !== 4 || $parts[0] !== 'TKT') {
        return response()->json([
            'success' => false,
            'message' => 'Format de billet invalide'
        ], 400);
    }

    $participantId = (int)$parts[3];
    
    $participant = Participant::with('user')
        ->where('id', $participantId)
        ->where('event_id', $eventId)
        ->first();

    if (!$participant) {
        return response()->json([
            'success' => false,
            'message' => 'Billet non trouvé pour cet événement'
        ], 404);
    }

    // Check if ticket is valid
    if (!in_array($participant->attendance_status, ['confirmed', 'attended'])) {
        return response()->json([
            'success' => false,
            'message' => 'Ce billet n\'est pas valide. Statut: ' . $participant->attendance_status
        ], 400);
    }

    // Check payment status for paid events
    if ($event->isPaid() && $participant->payment_status !== 'completed') {
        return response()->json([
            'success' => false,
            'message' => 'Paiement non complété. Statut: ' . $participant->payment_status
        ], 400);
    }

    // Check if already attended
    $alreadyAttended = $participant->attendance_status === 'attended';

    // Mark as attended if not already
    if (!$alreadyAttended) {
        $participant->update([
            'attendance_status' => 'attended'
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => $alreadyAttended ? 'Participant déjà enregistré comme présent' : 'Participant marqué comme présent',
        'data' => [
            'participant_id' => $participant->id,
            'participant_name' => $participant->user->name ?? 'Utilisateur inconnu',
            'participant_email' => $participant->user->email ?? 'N/A',
            'ticket_number' => $ticketNumber,
            'attendance_status' => 'attended',
            'already_attended' => $alreadyAttended,
            'registration_date' => $participant->registration_date->format('d/m/Y à H:i')
        ]
    ]);
}

/**
 * Confirm participation for free events
 */
public function confirmParticipation($eventId)
{
    $event = Event::findOrFail($eventId);

    // Find user's participation
    $participant = Participant::where('event_id', $eventId)
        ->where('user_id', Auth::id())
        ->where('attendance_status', 'registered')
        ->first();

    if (!$participant) {
        return redirect()->back()->with('error', 'Aucune inscription trouvée ou déjà confirmée.');
    }

    // Only for free events
    if ($event->isPaid()) {
        return redirect()->back()->with('error', 'Cette action n\'est disponible que pour les événements gratuits.');
    }

    // Update status to confirmed
    $participant->update([
        'attendance_status' => 'confirmed'
    ]);

    // Send ticket email
    try {
        Mail::to($participant->user->email)->send(
            new \App\Mail\TicketNotification($participant)
        );
        
        return redirect()->back()->with('success', 'Participation confirmée ! Consultez votre email pour votre billet.');
    } catch (\Exception $e) {
        \Log::error('Error sending ticket: ' . $e->getMessage());
        return redirect()->back()->with('warning', 'Participation confirmée, mais l\'envoi du billet par email a échoué. Vous pouvez le consulter sur cette page.');
    }
}

/**
 * QR Scanner interface for organizers
 */
public function qrScanner($eventId)
{
    $event = Event::with(['organizer', 'participants.user'])->findOrFail($eventId);

    // Check authorization - only event organizer
    if (Auth::id() !== $event->user_id) {
        abort(403, 'Seul l\'organisateur peut accéder au scanner QR.');
    }

    // Get statistics
    $stats = (object) [
        'total_confirmed' => $event->participants()
            ->where('attendance_status', '!=', 'cancelled')
            ->whereIn('attendance_status', ['confirmed', 'attended'])
            ->count(),
        'attended' => $event->participants()
            ->where('attendance_status', 'attended')
            ->count(),
    ];

    // Get attended participants
    $attendedParticipants = $event->participants()
        ->where('attendance_status', 'attended')
        ->with('user')
        ->orderBy('updated_at', 'desc')
        ->get();

    return view('FrontOffice.Events.qr-scanner', compact('event', 'stats', 'attendedParticipants'));
}


}
