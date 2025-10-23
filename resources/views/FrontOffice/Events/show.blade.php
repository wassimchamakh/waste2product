@extends('FrontOffice.layout1.app')

@section('title', $event->title . ' - Waste2Product')

@push('styles')
<style>
    .hero-image-overlay {
        background: linear-gradient(45deg, rgba(46, 125, 71, 0.8), rgba(6, 214, 160, 0.6));
    }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .tab-button.active {
        background-color: #2E7D47;
        color: white;
    }
    .progress-ring {
        transform: rotate(-90deg);
    }
    .progress-ring-circle {
        transition: stroke-dasharray 0.5s ease-in-out;
    }
    .event-type-workshop { border-left: 4px solid #2E7D47; }
    .event-type-collection { border-left: 4px solid #06D6A0; }
    .event-type-training { border-left: 4px solid #F4A261; }
    .event-type-repair_cafe { border-left: 4px solid #E76F51; }
    
    .badge-workshop { background-color: #2E7D47; }
    .badge-collection { background-color: #06D6A0; }
    .badge-training { background-color: #F4A261; }
    .badge-repair_cafe { background-color: #E76F51; }

    .modal {
        transition: all 0.3s ease-in-out;
    }
    .modal.show {
        opacity: 1;
        pointer-events: auto;
    }
    
    /* Participants Modal Styles */
    .participant-avatar {
        background: linear-gradient(135deg, #F4A261 0%, #E76F51 100%);
    }
    
    .status-confirmed { color: #06D6A0; }
    .status-pending { color: #F4A261; }
    .status-cancelled { color: #EF4444; }
    .status-attended { color: #2E7D47; }
    
    .participant-row {
        transition: all 0.2s ease;
    }
    .participant-row:hover {
        background-color: #f9fafb;
    }
    .participant-row.selected {
        background-color: #eff6ff;
        border-color: #2E7D47;
    }
    
    .bulk-actions {
        background: linear-gradient(135deg, #E76F51 0%, #F4A261 100%);
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }
    .bulk-actions.show {
        transform: translateY(0);
    }
</style>
@endpush

@section('content')
<!-- Flash Messages -->
@if(session('success'))
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-green-800">{{ session('success') }}</span>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-red-800">{{ session('error') }}</span>
        </div>
    </div>
</div>
@endif

<!-- Hero Section with Event Image -->
<section class="relative h-96 overflow-hidden">
    <img src="{{ $event->image ?? 'https://picsum.photos/1200/400?random=' . $event->id }}" 
         alt="{{ $event->title }}" 
         class="w-full h-full object-cover">
    <div class="absolute inset-0 hero-image-overlay"></div>
    
    <!-- Event Info Overlay -->
    <div class="absolute inset-0 flex items-end">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pb-8">
            <div class="flex flex-wrap gap-3 mb-4">
                <!-- Type Badge -->
                <span class="badge-{{ $event->type }} text-white px-4 py-2 rounded-full font-medium">
                    {{ $types[$event->type]['label'] }}
                </span>
                
                <!-- Status Badge -->
                @if($stats->current_participants >= $event->max_participants)
                    <span class="bg-accent text-white px-4 py-2 rounded-full font-medium">
                        <i class="fas fa-users mr-1"></i>Complet
                    </span>
                @else
                    <span class="bg-success text-white px-4 py-2 rounded-full font-medium">
                        <i class="fas fa-check mr-1"></i>Places disponibles
                    </span>
                @endif
            </div>
            
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                {{ $event->title }}
            </h1>
            
            <!-- Key Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-white">
                <div class="flex items-center">
                    <i class="fas fa-calendar-alt mr-3 text-xl"></i>
                    <div>
                        <div class="font-medium">{{ \Carbon\Carbon::parse($event->date_start)->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</div>
                        <div class="text-sm opacity-90">
                            {{ \Carbon\Carbon::parse($event->date_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->date_end)->format('H:i') }}
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <i class="fas fa-map-marker-alt mr-3 text-xl"></i>
                    <div>
                        <div class="font-medium">{{ $event->location }}</div>
                        <div class="text-sm opacity-90">{{ ucfirst($event->city) }}</div>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <i class="fas fa-user mr-3 text-xl"></i>
                    <div>
                        <div class="font-medium">{{ $event->organizer->name ?? $event->user->name ?? 'Organisateur' }}</div>
                        <div class="text-sm opacity-90">Organisateur</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Back Button -->
    <div class="absolute top-4 left-4">
        <a href="{{ route('Events.index') }}" class="bg-white/20 backdrop-blur-sm text-white p-3 rounded-full hover:bg-white/30 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Navigation Tabs -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="flex space-x-8">
                    <button class="tab-button active py-2 px-1 border-b-2 border-primary font-medium text-sm" data-tab="about">
                        À propos
                    </button>
                    <button class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700" data-tab="program">
                        Programme
                    </button>
                    <button class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700" data-tab="location">
                        Lieu
                    </button>
                    @if($isOrganizer && $event->isPaid())
                    <button class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700" data-tab="payments">
                        <i class="fas fa-euro-sign mr-1"></i> Paiements
                    </button>
                    <button class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700" data-tab="refunds">
                        <i class="fas fa-undo mr-1"></i> Remboursements
                    </button>
                    @endif
                    @if(auth()->check() && (auth()->user()->id == $event->user_id || $isParticipant))
                    <button class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700" data-tab="participants">
                        Participants
                    </button>
                    @endif
                    @if(\Carbon\Carbon::parse($event->date_end)->isPast())
                    <button class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700" data-tab="reviews">
                        Avis
                    </button>
                    @endif
                </nav>
            </div>

            <!-- Tab Contents -->
            <!-- About Tab -->
            <div id="about" class="tab-content active">
                <div class="prose max-w-none">
                    <h3 class="text-xl font-bold mb-4">Description de l'événement</h3>
                    <div class="text-gray-700 mb-6">
                        {{ $event->description ?? "Rejoignez-nous pour cet événement exceptionnel dans le cadre de l'économie circulaire. Une occasion unique d'apprendre, de partager et de contribuer à un avenir plus durable pour la Tunisie." }}
                    </div>
                    
                    @if($event->learning_objectives)
                    <h4 class="text-lg font-semibold mb-3">Ce que vous allez apprendre/faire :</h4>
                    <div class="text-gray-700 mb-6 whitespace-pre-line">
                        {{ $event->learning_objectives }}
                    </div>
                    @else
                    <h4 class="text-lg font-semibold mb-3">Ce que vous allez apprendre/faire :</h4>
                    <ul class="list-disc pl-5 space-y-2 text-gray-700 mb-6">
                        @if($event->type == 'workshop')
                            <li>Techniques pratiques de transformation et d'upcycling</li>
                            <li>Utilisation d'outils et de matériaux récupérés</li>
                            <li>Méthodes de création durable</li>
                        @elseif($event->type == 'collection')
                            <li>Tri et classification des déchets</li>
                            <li>Processus de recyclage et de valorisation</li>
                            <li>Impact environnemental et social</li>
                        @elseif($event->type == 'training')
                            <li>Concepts fondamentaux de l'économie circulaire</li>
                            <li>Applications pratiques dans le quotidien</li>
                            <li>Création de solutions durables</li>
                        @else
                            <li>Techniques de réparation et de maintenance</li>
                            <li>Diagnostic et résolution de problèmes</li>
                            <li>Prolongation de la durée de vie des objets</li>
                        @endif
                    </ul>
                    @endif
                    
                    @if($event->required_materials)
                    <h4 class="text-lg font-semibold mb-3">Matériel nécessaire :</h4>
                    <div class="text-gray-700 mb-6 whitespace-pre-line">
                        {{ $event->required_materials }}
                    </div>
                    @else
                    <h4 class="text-lg font-semibold mb-3">Matériel nécessaire :</h4>
                    <p class="text-gray-700 mb-6">
                        @if($event->type == 'workshop' || $event->type == 'repair_cafe')
                            Apportez vos objets à réparer ou transformer. Outils de base fournis sur place.
                        @else
                            Aucun matériel spécifique requis. Tout sera fourni sur place.
                        @endif
                    </p>
                    @endif
                    
                    <h4 class="text-lg font-semibold mb-3">Niveau requis :</h4>
                    <span class="inline-block bg-success text-white px-3 py-1 rounded-full text-sm">
                        @php
                            $skillLevels = [
                                'beginner' => 'Débutant - Tous niveaux bienvenus',
                                'intermediate' => 'Intermédiaire',
                                'advanced' => 'Avancé',
                                'all' => 'Tous niveaux'
                            ];
                        @endphp
                        {{ $skillLevels[$event->skill_level ?? 'beginner'] }}
                    </span>
                </div>
            </div>

            <!-- Program Tab -->
            <div id="program" class="tab-content">
                <h3 class="text-xl font-bold mb-6">Programme de l'événement</h3>
                <div class="space-y-4">
                    @if($event->program && count($event->program) > 0)
                        {{-- Custom program from database --}}
                        @foreach($event->program as $segment)
                        <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-16 text-center">
                                <div class="bg-primary text-white px-3 py-1 rounded text-sm font-medium">
                                    {{ $segment['time'] ?? '' }}
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $segment['title'] ?? '' }}</h4>
                                @if(isset($segment['description']))
                                <p class="text-sm text-gray-600 mt-1">{{ $segment['description'] }}</p>
                                @endif
                                @if(isset($segment['duration']))
                                <p class="text-sm text-gray-600 mt-1">Durée : {{ $segment['duration'] }} minutes</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                        {{-- Default auto-generated program --}}
                        @php
                            $startTime = \Carbon\Carbon::parse($event->date_start);
                            $endTime = \Carbon\Carbon::parse($event->date_end);
                            $duration = $startTime->diffInMinutes($endTime);
                            $segments = [
                                ['time' => $startTime->format('H:i'), 'title' => 'Accueil et présentation', 'duration' => 30],
                                ['time' => $startTime->copy()->addMinutes(30)->format('H:i'), 'title' => 'Activité principale', 'duration' => $duration - 90],
                                ['time' => $endTime->copy()->subMinutes(30)->format('H:i'), 'title' => 'Bilan et échanges', 'duration' => 30]
                            ];
                        @endphp
                        
                        @foreach($segments as $segment)
                        <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-16 text-center">
                                <div class="bg-primary text-white px-3 py-1 rounded text-sm font-medium">
                                    {{ $segment['time'] }}
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $segment['title'] }}</h4>
                                <p class="text-sm text-gray-600">Durée : {{ $segment['duration'] }} minutes</p>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Location Tab -->
            <div id="location" class="tab-content">
                <h3 class="text-xl font-bold mb-6">Lieu de l'événement</h3>
                
                <!-- Google Maps Display -->
                @if($event->latitude && $event->longitude)
                    <!-- Interactive Map with Coordinates -->
                    <div class="mb-6 rounded-lg overflow-hidden shadow-md" style="height: 400px;">
                        <iframe 
                            width="100%" 
                            height="100%" 
                            frameborder="0" 
                            style="border:0"
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q={{ $event->latitude }},{{ $event->longitude }}&zoom=15"
                            allowfullscreen>
                        </iframe>
                    </div>
                @elseif($event->maps_link)
                    <!-- Embedded Map from Google Maps Link -->
                    @php
                        // Extract coordinates or place ID from Google Maps link
                        $embedUrl = '';
                        if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $event->maps_link, $matches)) {
                            // Link contains coordinates
                            $embedUrl = "https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q={$matches[1]},{$matches[2]}&zoom=15";
                        } elseif (preg_match('/place\/([^\/]+)/', $event->maps_link, $matches)) {
                            // Link contains place name
                            $placeName = urlencode(str_replace('+', ' ', $matches[1]));
                            $embedUrl = "https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q={$placeName}";
                        } else {
                            // Fallback: search by location address
                            $embedUrl = "https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=" . urlencode($event->location);
                        }
                    @endphp
                    <div class="mb-6 rounded-lg overflow-hidden shadow-md" style="height: 400px;">
                        <iframe 
                            width="100%" 
                            height="100%" 
                            frameborder="0" 
                            style="border:0"
                            referrerpolicy="no-referrer-when-downgrade"
                            src="{{ $embedUrl }}"
                            allowfullscreen>
                        </iframe>
                    </div>
                @else
                    <!-- Fallback: Search by Address -->
                    <div class="mb-6 rounded-lg overflow-hidden shadow-md" style="height: 400px;">
                        <iframe 
                            width="100%" 
                            height="100%" 
                            frameborder="0" 
                            style="border:0"
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q={{ urlencode($event->location) }}"
                            allowfullscreen>
                        </iframe>
                    </div>
                @endif
                
                @if($event->maps_link)
                <div class="mb-6">
                    <a href="{{ $event->maps_link }}" target="_blank" 
                       class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Ouvrir dans Google Maps
                    </a>
                </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold mb-3">Adresse complète</h4>
                        <p class="text-gray-700 mb-4">{{ $event->location }}</p>
                        
                        @if($event->access_instructions)
                        <h4 class="font-semibold mb-3">Instructions d'accès</h4>
                        <div class="text-gray-700 whitespace-pre-line">
                            {{ $event->access_instructions }}
                        </div>
                        @else
                        <h4 class="font-semibold mb-3">Instructions d'accès</h4>
                        <p class="text-gray-700">
                            Accessible en transport en commun. Station de métro/bus la plus proche à 5 minutes à pied.
                        </p>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-semibold mb-3">Informations pratiques</h4>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-center">
                                <i class="fas fa-car mr-2 {{ $event->parking_available ? 'text-primary' : 'text-gray-400' }}"></i>
                                <span class="{{ !$event->parking_available ? 'text-gray-400' : '' }}">
                                    Parking {{ $event->parking_available ? 'disponible' : 'non disponible' }}
                                </span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-wheelchair mr-2 {{ $event->accessible_pmr ? 'text-primary' : 'text-gray-400' }}"></i>
                                <span class="{{ !$event->accessible_pmr ? 'text-gray-400' : '' }}">
                                    {{ $event->accessible_pmr ? 'Accessible PMR' : 'Non accessible PMR' }}
                                </span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-wifi mr-2 {{ $event->wifi_available ? 'text-primary' : 'text-gray-400' }}"></i>
                                <span class="{{ !$event->wifi_available ? 'text-gray-400' : '' }}">
                                    WiFi {{ $event->wifi_available ? 'gratuit' : 'non disponible' }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Payment Dashboard (Only for Organizer of Paid Events) -->
            @if($isOrganizer && $event->isPaid() && $paymentStats)
            <div id="payments" class="tab-content">
                <h3 class="text-xl font-bold mb-6 flex items-center">
                    <i class="fas fa-euro-sign mr-2 text-green-600"></i>
                    Tableau de Bord des Paiements
                </h3>
                
                <!-- Payment Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Total Revenue -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-green-700">Revenus Totaux</span>
                            <i class="fas fa-money-bill-wave text-green-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-green-900">
                            {{ number_format($paymentStats->total_revenue, 3) }} EUR
                        </div>
                        <div class="text-xs text-green-600 mt-1">
                            {{ $paymentStats->paid_participants }} paiements confirmés
                        </div>
                    </div>
                    
                    <!-- Paid Participants -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-blue-700">Participants Payés</span>
                            <i class="fas fa-check-circle text-blue-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-blue-900">
                            {{ $paymentStats->paid_participants }}
                        </div>
                        <div class="text-xs text-blue-600 mt-1">
                            sur {{ $stats->current_participants }} inscrits
                        </div>
                    </div>
                    
                    <!-- Pending Payments -->
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-yellow-700">Paiements en Attente</span>
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-yellow-900">
                            {{ $paymentStats->pending_payments }}
                        </div>
                        <div class="text-xs text-yellow-600 mt-1">
                            À confirmer
                        </div>
                    </div>
                    
                    <!-- Refunds -->
                    <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-red-700">Remboursements</span>
                            <i class="fas fa-undo text-red-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-red-900">
                            {{ number_format($paymentStats->total_refunded, 3) }} EUR
                        </div>
                        <div class="text-xs text-red-600 mt-1">
                            {{ $paymentStats->refunded }} participant(s)
                        </div>
                    </div>
                </div>
                
                <!-- Participants with Payment Status -->
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                        <h4 class="font-semibold text-gray-800">Liste des Participants</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Participant
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statut Paiement
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Montant
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Facture
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($event->participants()->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])->with('user')->get() as $participant)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white font-bold">
                                                    {{ substr($participant->user->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $participant->user->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $participant->user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($participant->payment_status === 'completed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Payé
                                            </span>
                                        @elseif($participant->payment_status === 'pending_payment')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i> En attente
                                            </span>
                                        @elseif($participant->payment_status === 'failed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i> Échoué
                                            </span>
                                        @elseif($participant->payment_status === 'refunded')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                <i class="fas fa-undo mr-1"></i> Remboursé
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $participant->payment_status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($participant->amount_paid)
                                            {{ number_format($participant->amount_paid, 3) }} EUR
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($participant->payment_completed_at)
                                            {{ $participant->payment_completed_at->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($participant->invoice_number)
                                            <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">
                                                {{ $participant->invoice_number }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Export Button -->
                <div class="mt-6 text-center">
                    <button onclick="exportPaymentData()" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-medium">
                        <i class="fas fa-download mr-2"></i>Exporter les Données de Paiement (CSV)
                    </button>
                </div>
            </div>
            @endif

            <!-- Refunds Tab -->
            @if($isOrganizer && $event->isPaid())
            <div id="refunds" class="tab-content">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold flex items-center">
                        <i class="fas fa-undo mr-2 text-orange-600"></i>
                        Demandes de Remboursement
                    </h3>
                    <a href="{{ route('Events.refund.list', $event->id) }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <i class="fas fa-external-link-alt mr-2"></i>Gérer Toutes les Demandes
                    </a>
                </div>

                @php
                    $pendingRefunds = \App\Models\RefundRequest::where('event_id', $event->id)
                        ->where('status', 'pending')
                        ->with(['participant.user'])
                        ->latest()
                        ->take(5)
                        ->get();
                    $totalRefunds = \App\Models\RefundRequest::where('event_id', $event->id)->count();
                @endphp

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-yellow-700">En Attente</span>
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-yellow-900 mt-2">
                            {{ \App\Models\RefundRequest::where('event_id', $event->id)->where('status', 'pending')->count() }}
                        </div>
                    </div>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-green-700">Approuvées</span>
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-green-900 mt-2">
                            {{ \App\Models\RefundRequest::where('event_id', $event->id)->where('status', 'completed')->count() }}
                        </div>
                    </div>
                    
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-red-700">Rejetées</span>
                            <i class="fas fa-times-circle text-red-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-red-900 mt-2">
                            {{ \App\Models\RefundRequest::where('event_id', $event->id)->where('status', 'rejected')->count() }}
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-blue-700">Total</span>
                            <i class="fas fa-list text-blue-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-blue-900 mt-2">
                            {{ $totalRefunds }}
                        </div>
                    </div>
                </div>

                @if($pendingRefunds->count() > 0)
                <!-- Recent Pending Refund Requests -->
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                        <h4 class="font-semibold text-gray-800">Demandes Récentes en Attente</h4>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($pendingRefunds as $refund)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h5 class="font-semibold text-gray-900">{{ $refund->participant->user->name }}</h5>
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                            En Attente
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm text-gray-600 mb-2">
                                        <div>
                                            <i class="fas fa-money-bill-wave w-4 mr-1"></i>
                                            {{ number_format($refund->refund_amount, 3) }} {{ config('payments.currency') }}
                                        </div>
                                        <div>
                                            <i class="fas fa-calendar w-4 mr-1"></i>
                                            {{ \Carbon\Carbon::parse($refund->created_at)->format('d/m/Y') }}
                                        </div>
                                        <div>
                                            <i class="fas fa-tag w-4 mr-1"></i>
                                            {{ ucfirst(str_replace('_', ' ', $refund->reason_category)) }}
                                        </div>
                                        <div>
                                            <i class="fas fa-envelope w-4 mr-1"></i>
                                            {{ $refund->participant->user->email }}
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-700 mb-3">
                                        <strong>Raison:</strong> {{ $refund->reason }}
                                    </p>
                                    <div class="flex gap-2">
                                        <form action="{{ route('Events.refund.approve', [$event->id, $refund->id]) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Approuver ce remboursement de {{ number_format($refund->refund_amount, 3) }} {{ config('payments.currency') }} ?')"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-medium transition-colors">
                                                <i class="fas fa-check mr-1"></i>Approuver
                                            </button>
                                        </form>
                                        <button onclick="showQuickReject({{ $refund->id }})" 
                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium transition-colors">
                                            <i class="fas fa-times mr-1"></i>Rejeter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('Events.refund.list', $event->id) }}" 
                       class="text-blue-600 hover:text-blue-800 font-medium">
                        Voir toutes les demandes ({{ $totalRefunds }}) →
                    </a>
                </div>
                @else
                <div class="bg-gray-50 rounded-lg p-12 text-center">
                    <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-700 mb-2">Aucune Demande en Attente</h4>
                    <p class="text-gray-500">Les demandes de remboursement apparaîtront ici.</p>
                    @if($totalRefunds > 0)
                    <a href="{{ route('Events.refund.list', $event->id) }}" 
                       class="inline-block mt-4 text-blue-600 hover:text-blue-800 font-medium">
                        Voir toutes les demandes ({{ $totalRefunds }})
                    </a>
                    @endif
                </div>
                @endif
            </div>
            @endif

            <!-- Participants Tab -->
            @if(auth()->check() && (auth()->user()->id == $event->user_id || $isParticipant))
            <div id="participants" class="tab-content">
                <h3 class="text-xl font-bold mb-6">Participants inscrits</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @for($i = 1; $i <= min($stats->current_participants, 12); $i++)
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold">
                            {{ chr(64 + $i) }}
                        </div>
                        <div>
                            <div class="font-medium">Participant {{ $i }}</div>
                            <div class="text-sm text-gray-500">Inscrit</div>
                        </div>
                    </div>
                    @endfor
                    
                    @if($stats->current_participants > 12)
                    <div class="flex items-center justify-center p-3 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <span class="text-gray-500 text-sm">
                            +{{ $stats->current_participants - 12 }} autres participants
                        </span>
                    </div>
                    @endif
                </div>
                
                @if(auth()->user()->id == $event->user_id)
                <div class="mt-6 flex gap-3 justify-center flex-wrap">
                    <a href="{{ route('Events.qr.scanner', $event->id) }}" 
                       class="bg-gradient-to-r from-purple-600 to-violet-600 hover:from-purple-700 hover:to-violet-700 text-white px-6 py-3 rounded-lg transition-all font-medium shadow-md hover:shadow-lg">
                        <i class="fas fa-qrcode mr-2"></i>Scanner QR
                    </a>
                    
                    <button onclick="showParticipantsModal()" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors font-medium">
                        <i class="fas fa-users mr-2"></i>Gérer tous les participants
                    </button>
                    
                    @if(\Carbon\Carbon::parse($event->date_end)->isPast())
                    @php
                        $attendedCount = $event->participants()->where('attendance_status', 'attended')->count();
                    @endphp
                    @if($attendedCount > 0)
                    <form action="{{ route('Events.certificates.send', $event->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Envoyer les certificats à {{ $attendedCount }} participant(s) présent(s) ?')"
                                class="bg-gradient-to-r from-green-600 to-blue-600 text-white px-6 py-3 rounded-lg hover:from-green-700 hover:to-blue-700 transition-all font-medium shadow-md hover:shadow-lg">
                            <i class="fas fa-certificate mr-2"></i>Envoyer Certificats ({{ $attendedCount }})
                        </button>
                    </form>
                    @endif
                    @endif
                </div>
                @endif
            </div>
            @endif

            <!-- Reviews Tab -->
            @if(\Carbon\Carbon::parse($event->date_end)->isPast())
            <div id="reviews" class="tab-content">
                <h3 class="text-xl font-bold mb-6">Avis des participants</h3>
                
                @php
                    // Get all feedback from participants
                    $feedbackParticipants = $event->participants()
                        ->whereNotNull('feedback')
                        ->where('feedback', '!=', '')
                        ->with('user')
                        ->get();
                    
                    // Calculate average rating
                    $ratings = $feedbackParticipants->pluck('rating')->filter();
                    $averageRating = $ratings->isNotEmpty() ? round($ratings->average(), 1) : 0;
                    $totalFeedback = $feedbackParticipants->count();
                @endphp
                
                @if($totalFeedback > 0)
                    <!-- Rating Summary -->
                    <div class="mb-6 bg-gradient-to-r from-warning/10 to-orange-50 rounded-xl p-6 border border-warning/20">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <div class="text-5xl font-bold text-warning">{{ $averageRating }}</div>
                                <div>
                                    <div class="flex text-warning mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $averageRating ? '' : 'opacity-30' }}"></i>
                                        @endfor
                                    </div>
                                    <div class="text-sm text-gray-600">Basé sur {{ $totalFeedback }} avis</div>
                                </div>
                            </div>
                            
                            @if($isOrganizer)
                                <button onclick="window.location.href='{{ route('Events.sentiment.results', $event->id) }}'" 
                                        class="bg-white text-primary border border-primary px-4 py-2 rounded-lg hover:bg-primary hover:text-white transition-all">
                                    <i class="fas fa-chart-bar mr-2"></i>
                                    Analyse IA
                                </button>
                            @endif
                        </div>
                        
                        <!-- Rating Breakdown -->
                        @php
                            $ratingCounts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                            foreach($feedbackParticipants as $p) {
                                if($p->rating) $ratingCounts[$p->rating]++;
                            }
                        @endphp
                        <div class="space-y-2">
                            @foreach([5,4,3,2,1] as $star)
                                @php
                                    $count = $ratingCounts[$star];
                                    $percentage = $totalFeedback > 0 ? ($count / $totalFeedback) * 100 : 0;
                                @endphp
                                <div class="flex items-center space-x-3 text-sm">
                                    <span class="w-12 text-gray-600">{{ $star }} <i class="fas fa-star text-warning text-xs"></i></span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="bg-warning h-2 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="w-12 text-right text-gray-600">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Individual Reviews -->
                    <div class="space-y-4">
                        @foreach($feedbackParticipants as $participant)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start space-x-3">
                                <!-- Avatar -->
                                <div class="w-12 h-12 bg-gradient-to-br from-primary to-success rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                                    {{ strtoupper(substr($participant->user->name ?? 'U', 0, 1)) }}
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <!-- Header -->
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <div class="font-medium text-gray-900">
                                                {{ $participant->user->name ?? 'Participant' }}
                                            </div>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <!-- Stars -->
                                                <div class="flex text-warning text-sm">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= ($participant->rating ?? 0) ? '' : 'text-gray-300' }}"></i>
                                                    @endfor
                                                </div>
                                                <!-- Date -->
                                                <span class="text-xs text-gray-500">
                                                    {{ $participant->updated_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Sentiment Badge (if analyzed) -->
                                        @if($participant->sentiment_label)
                                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $participant->sentiment_label === 'positive' ? 'bg-green-100 text-green-700' : ($participant->sentiment_label === 'negative' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                                                @if($participant->sentiment_label === 'positive')
                                                    😊 Positif
                                                @elseif($participant->sentiment_label === 'negative')
                                                    😞 Négatif
                                                @else
                                                    😐 Neutre
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Feedback Text -->
                                    <p class="text-gray-700 text-sm leading-relaxed">
                                        {{ $participant->feedback }}
                                    </p>
                                    
                                    <!-- Themes (if available) -->
                                    @if($participant->feedback_themes)
                                        @php
                                            $themes = is_array($participant->feedback_themes) 
                                                ? $participant->feedback_themes 
                                                : json_decode($participant->feedback_themes, true);
                                        @endphp
                                        @if(!empty($themes))
                                            <div class="flex flex-wrap gap-2 mt-3">
                                                <span class="text-xs text-gray-500">Thèmes:</span>
                                                @foreach($themes as $theme)
                                                    <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded text-xs">
                                                        {{ $theme }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                    
                                    <!-- Organizer Only: Detailed Sentiment -->
                                    @if($isOrganizer && $participant->sentiment_score !== null)
                                        <div class="mt-3 pt-3 border-t border-gray-100">
                                            <div class="flex items-center space-x-4 text-xs text-gray-600">
                                                <span>
                                                    <i class="fas fa-chart-line mr-1"></i>
                                                    Score: <strong>{{ round($participant->sentiment_score, 2) }}</strong>
                                                </span>
                                                <span>
                                                    <i class="fas fa-percentage mr-1"></i>
                                                    Confiance: <strong>{{ round($participant->sentiment_confidence * 100, 0) }}%</strong>
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- AI Analysis Button for Organizer -->
                    @if($isOrganizer)
                        <div class="mt-6 text-center">
                            <button onclick="window.location.href='{{ route('Events.sentiment.results', $event->id) }}'" 
                                    class="bg-gradient-to-r from-primary to-success text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all">
                                <i class="fas fa-robot mr-2"></i>
                                Voir l'analyse complète avec IA
                            </button>
                        </div>
                    @endif
                @else
                    <!-- No Feedback Yet -->
                    <div class="text-center py-12 bg-gray-50 rounded-xl">
                        <div class="text-6xl mb-4">💬</div>
                        <h4 class="text-lg font-medium text-gray-700 mb-2">Aucun avis pour le moment</h4>
                        <p class="text-sm text-gray-600">
                            Les participants pourront laisser leur avis après avoir assisté à l'événement.
                        </p>
                    </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Registration Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-8">
                <!-- Price -->
                <div class="text-center mb-6">
                    @if($event->isFree())
                        <div class="text-3xl font-bold text-success mb-2">Gratuit</div>
                    @else
                        @php
                            $currentPrice = $event->getCurrentPrice();
                            $hasDiscount = $event->hasActiveEarlyBird() || ($event->group_discount_size && auth()->check());
                        @endphp
                        
                        @if($hasDiscount && $currentPrice < $event->price)
                            <div class="text-lg text-gray-400 line-through mb-1">{{ number_format($event->price, 3) }} TND</div>
                            <div class="text-3xl font-bold text-accent mb-2">{{ number_format($currentPrice, 3) }} TND</div>
                            @if($event->hasActiveEarlyBird())
                                <div class="inline-flex items-center bg-orange-100 text-orange-800 text-xs font-semibold px-3 py-1 rounded-full">
                                    <i class="fas fa-bolt mr-1"></i>Early Bird
                                </div>
                            @endif
                        @else
                            <div class="text-3xl font-bold text-primary mb-2">{{ number_format($currentPrice, 3) }} TND</div>
                        @endif
                    @endif
                    <div class="text-sm text-gray-600">Par participant</div>
                    
                    @if($event->isPaid() && $event->cancellation_policy)
                        <div class="mt-3 text-xs text-gray-500">
                            <i class="fas fa-shield-alt mr-1"></i>
                            {{ $event->getCancellationPolicyLabel() }}
                        </div>
                    @endif
                </div>

                <!-- Participants Progress -->
                <div class="mb-6">
                    @php
                        $progressPercent = $event->max_participants > 0 ? ($stats->current_participants / $event->max_participants) * 100 : 0;
                    @endphp
                    
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">
                            <i class="fas fa-users mr-1"></i>
                            {{ $stats->current_participants }}/{{ $event->max_participants }} inscrits
                        </span>
                        <span class="text-sm font-bold">{{ round($progressPercent) }}%</span>
                    </div>
                    
                    <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                        <div class="bg-gradient-to-r from-success to-primary h-3 rounded-full transition-all duration-500" 
                             style="width: {{ $progressPercent }}%"></div>
                    </div>
                    
                    <!-- Countdown -->
                    @if(\Carbon\Carbon::parse($event->date_start)->isFuture())
                        <div class="text-center">
                            <div class="text-sm text-gray-600 mb-1">Commence dans</div>
                            <div class="text-lg font-bold text-accent" id="countdown">
                                {{ \Carbon\Carbon::parse($event->date_start)->diffForHumans() }}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Registration Button -->
                @php $isFull = $stats->current_participants >= $event->max_participants; @endphp
                
                @auth
                    @if(auth()->user()->id == $event->user_id)
                        <!-- Owner Actions -->
                        <div class="space-y-3">
                            <div class="text-center text-sm text-gray-600 mb-4">
                                <i class="fas fa-crown mr-1 text-warning"></i>
                                Vous êtes l'organisateur
                            </div>
                            <a href="{{ route('Events.edit', $event) }}" class="w-full bg-secondary text-white px-4 py-3 rounded-lg hover:bg-orange-500 transition-colors text-center font-medium block">
                                <i class="fas fa-edit mr-2"></i>Modifier l'événement
                            </a>
                            <button onclick="showParticipantsModal()" class="w-full border border-primary text-primary px-4 py-3 rounded-lg hover:bg-primary hover:text-white transition-colors text-center font-medium">
                                <i class="fas fa-users mr-2"></i>Voir les participants
                            </button>
                            <button onclick="showDeleteModal()" class="w-full border border-accent text-accent px-4 py-3 rounded-lg hover:bg-accent hover:text-white transition-colors text-center font-medium">
                                <i class="fas fa-trash mr-2"></i>Supprimer l'événement
                            </button>
                        </div>
                    @elseif($isParticipant)
                        <!-- Already Registered -->
                        <div class="text-center mb-4">
                            @php
                                $participant = $event->participants()->where('user_id', auth()->id())->first();
                            @endphp
                            
                            <div class="flex items-center justify-center mb-3">
                                @if($participant && $participant->payment_status === 'completed')
                                    <span class="inline-flex items-center bg-green-100 text-green-800 px-4 py-2 rounded-full font-semibold text-sm">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Inscrit & Payé
                                    </span>
                                @elseif($participant && $participant->payment_status === 'pending_payment')
                                    <span class="inline-flex items-center bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full font-semibold text-sm">
                                        <i class="fas fa-clock mr-2"></i>
                                        Paiement en attente
                                    </span>
                                @else
                                    <span class="inline-flex items-center bg-green-100 text-green-800 px-4 py-2 rounded-full font-semibold text-sm">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Vous êtes inscrit(e)
                                    </span>
                                @endif
                            </div>
                            
                            @if($participant && $participant->payment_status === 'pending_payment')
                                <div class="mb-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                    <p class="text-sm text-yellow-800 mb-3">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Veuillez compléter votre paiement
                                    </p>
                                    <a href="{{ route('Events.payment', ['event' => $event->id, 'participant' => $participant->id]) }}" 
                                       class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors font-medium inline-block text-center">
                                        <i class="fas fa-credit-card mr-2"></i>Payer maintenant
                                    </a>
                                </div>
                            @elseif($userParticipation && $userParticipation->attendance_status === 'registered' && !$event->isPaid())
                                <!-- Confirm Participation Button for FREE events -->
                                <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-center mb-3">
                                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-check text-white"></i>
                                        </div>
                                        <div>
                                            <h5 class="font-semibold text-blue-900">Confirmer votre participation</h5>
                                            <p class="text-xs text-blue-700">Recevez votre billet par email</p>
                                        </div>
                                    </div>
                                    <form action="{{ route('Events.confirm.participation', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg transition-colors font-medium">
                                            <i class="fas fa-check-circle mr-2"></i>Confirmer ma participation
                                        </button>
                                    </form>
                                </div>
                            @elseif($userParticipation && in_array($userParticipation->attendance_status, ['confirmed', 'attended']))
                                <!-- View Ticket Button -->
                                <div class="mb-4 bg-gradient-to-r from-purple-50 to-violet-50 border-l-4 border-purple-500 rounded-lg p-4">
                                    <div class="flex items-center mb-3">
                                        <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-ticket-alt text-white"></i>
                                        </div>
                                        <div>
                                            <h5 class="font-semibold text-purple-900">Votre Billet</h5>
                                            <p class="text-xs text-purple-700">
                                                @if($userParticipation->attendance_status === 'attended')
                                                    Présence confirmée ✓
                                                @else
                                                    Prêt à utiliser
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('Events.ticket.view', [$event->id, $userParticipation->id]) }}" 
                                       target="_blank"
                                       class="w-full bg-gradient-to-r from-purple-600 to-violet-600 hover:from-purple-700 hover:to-violet-700 text-white px-4 py-3 rounded-lg transition-all font-medium text-center block shadow-md hover:shadow-lg">
                                        <i class="fas fa-ticket-alt mr-2"></i>Voir Mon Billet
                                    </a>
                                </div>
                            @else
                                <div class="text-sm text-gray-600">
                                    Confirmation envoyée par email
                                </div>
                            @endif
                            
                            @if($participant && $participant->invoice_number)
                                <div class="mt-3 text-xs text-gray-500">
                                    <i class="fas fa-file-invoice mr-1"></i>
                                    Facture: {{ $participant->invoice_number }}
                                </div>
                            @endif
                        </div>
                        
                        @if(\Carbon\Carbon::parse($event->date_start)->isFuture())
                            <div class="space-y-2">
                                <!-- Refund Status or Button -->
                                @if($pendingRefundRequest)
                                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-500 rounded-lg p-4 shadow-sm">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-hourglass-half text-white"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <h4 class="font-semibold text-yellow-900 mb-1">Remboursement en Cours</h4>
                                                <div class="space-y-1.5">
                                                    <div class="flex items-center text-sm text-yellow-800">
                                                        <i class="fas fa-money-bill-wave w-4 mr-2"></i>
                                                        <span class="font-medium">{{ number_format($pendingRefundRequest->refund_amount, 3) }} {{ config('payments.currency') }}</span>
                                                    </div>
                                                    <div class="flex items-center text-sm text-yellow-700">
                                                        <i class="fas fa-calendar-alt w-4 mr-2"></i>
                                                        <span>Demandé le {{ \Carbon\Carbon::parse($pendingRefundRequest->created_at)->format('d/m/Y') }}</span>
                                                    </div>
                                                    <div class="flex items-start text-xs text-yellow-600 bg-yellow-100 rounded px-2 py-1.5 mt-2">
                                                        <i class="fas fa-info-circle mt-0.5 mr-2"></i>
                                                        <span>L'organisateur examine votre demande. Vous recevrez une notification par email.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($userParticipation && $userParticipation->canRequestRefund())
                                    <a href="{{ route('Events.refund.form', [$event->id, $userParticipation->id]) }}" 
                                       class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-3 rounded-lg transition-colors font-medium text-center block">
                                        <i class="fas fa-undo mr-2"></i>Demander un remboursement
                                    </a>
                                @endif
                                
                                <!-- Unregister Button -->
                                @if($userParticipation && $userParticipation->payment_status !== 'completed')
                                    <form action="{{ route('Events.unregister', $event) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full border border-accent text-accent px-4 py-3 rounded-lg hover:bg-accent hover:text-white transition-colors font-medium" 
                                                onclick="return confirmUnregister()">
                                            <i class="fas fa-user-minus mr-2"></i>Se désinscrire
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @elseif($userParticipation && $userParticipation->attendance_status === 'attended')
                            <!-- Ticket Button (for attended) -->
                            <div class="bg-gradient-to-r from-purple-50 to-violet-50 border-l-4 border-purple-500 rounded-lg p-4 mb-3">
                                <div class="flex items-center mb-3">
                                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-ticket-alt text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-purple-900">Votre Billet</h4>
                                        <p class="text-sm text-purple-700">Présence confirmée ✓</p>
                                    </div>
                                </div>
                                <a href="{{ route('Events.ticket.view', [$event->id, $userParticipation->id]) }}" 
                                   target="_blank"
                                   class="w-full bg-gradient-to-r from-purple-600 to-violet-600 hover:from-purple-700 hover:to-violet-700 text-white px-4 py-3 rounded-lg transition-all font-medium text-center block shadow-md hover:shadow-lg">
                                    <i class="fas fa-ticket-alt mr-2"></i>Voir Mon Billet
                                </a>
                            </div>
                            
                            <!-- Certificate Button -->
                            <div class="bg-gradient-to-r from-green-50 to-blue-50 border-l-4 border-green-500 rounded-lg p-4 mb-3">
                                <div class="flex items-center mb-3">
                                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-certificate text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-green-900">Événement Complété !</h4>
                                        <p class="text-sm text-green-700">Téléchargez votre certificat</p>
                                    </div>
                                </div>
                                <a href="{{ route('Events.certificate.view', [$event->id, $userParticipation->id]) }}" 
                                   target="_blank"
                                   class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-3 rounded-lg transition-all font-medium text-center block shadow-md hover:shadow-lg">
                                    <i class="fas fa-certificate mr-2"></i>Voir Mon Certificat
                                </a>
                            </div>
                            
                            <!-- Show Feedback Button for Attended Users -->
                            @if($userParticipation->feedback)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-3">
                                    <div class="text-center">
                                        <i class="fas fa-check-circle text-success text-2xl mb-2"></i>
                                        <p class="text-sm font-medium text-green-700 mb-1">Avis envoyé</p>
                                        <div class="flex justify-center space-x-1 mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star text-sm {{ $i <= $userParticipation->rating ? 'text-warning' : 'text-gray-300' }}"></i>
                                            @endfor
                                        </div>
                                        <button onclick="showFeedbackModal()" class="text-xs text-primary hover:underline">
                                            Voir mon avis
                                        </button>
                                    </div>
                                </div>
                            @else
                                <button onclick="showFeedbackModal()" class="w-full bg-gradient-to-r from-warning to-orange-500 text-white px-4 py-3 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all font-medium">
                                    <i class="fas fa-star mr-2"></i>Laisser un avis
                                </button>
                                <p class="text-sm text-gray-600 text-center mt-2">
                                    <i class="fas fa-robot mr-1"></i>
                                    Analysé par IA
                                </p>
                            @endif
                        @endif
                    @elseif($isFull)
                        <!-- Event Full -->
                        <button disabled class="w-full bg-gray-300 text-gray-500 px-4 py-3 rounded-lg cursor-not-allowed font-medium">
                            <i class="fas fa-users mr-2"></i>Événement complet
                        </button>
                        <p class="text-sm text-gray-600 text-center mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Contactez l'organisateur pour la liste d'attente
                        </p>
                    @elseif(\Carbon\Carbon::parse($event->date_start)->isPast())
                        <!-- Event Past -->
                        <button disabled class="w-full bg-gray-300 text-gray-500 px-4 py-3 rounded-lg cursor-not-allowed font-medium">
                            <i class="fas fa-clock mr-2"></i>Événement terminé
                        </button>
                    @else
                        <!-- Register Button -->
                        @if($event->isPaid())
                            <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-700">Prix:</span>
                                    <span class="text-lg font-bold text-primary">
                                        {{ number_format($event->getCurrentPrice(), 3) }} TND
                                    </span>
                                </div>
                                @if($event->payment_deadline_hours)
                                    <p class="text-xs text-gray-600">
                                        <i class="fas fa-clock mr-1"></i>
                                        Paiement requis dans les {{ $event->payment_deadline_hours }}h après inscription
                                    </p>
                                @endif
                            </div>
                        @endif
                        
                        <form action="{{ route('Events.register', $event) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-primary text-white px-4 py-3 rounded-lg hover:bg-green-600 transition-colors font-medium">
                                <i class="fas fa-user-plus mr-2"></i>
                                @if($event->isPaid())
                                    S'inscrire et payer
                                @else
                                    S'inscrire maintenant
                                @endif
                            </button>
                        </form>
                        <p class="text-sm text-gray-600 text-center mt-2">
                            <i class="fas fa-envelope mr-1"></i>
                            Confirmation par email
                        </p>
                        
                        @if($event->isPaid())
                            <div class="mt-3 flex items-center justify-center text-xs text-gray-500">
                                <i class="fas fa-lock mr-1"></i>
                                Paiement sécurisé par Stripe
                            </div>
                        @endif
                    @endif
                @else
                    <!-- Login Required -->
                    <a href="{{ route('login') }}" class="w-full bg-primary text-white px-4 py-3 rounded-lg hover:bg-green-600 transition-colors text-center font-medium block">
                        <i class="fas fa-sign-in-alt mr-2"></i>Connexion pour s'inscrire
                    </a>
                @endauth
            </div>

            <!-- Organizer Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
                <h4 class="font-bold mb-4">Organisateur</h4>
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-primary to-success rounded-full flex items-center justify-center text-white font-bold text-lg">
                        {{ substr($event->organizer->name ?? 'O', 0, 1) }}
                    </div>
                    <div>
                        <div class="font-medium">{{ $event->organizer->name ?? $event->user->name ?? 'Organisateur' }}</div>
                        <div class="text-sm text-gray-600">{{ rand(3, 15) }} événements organisés</div>
                    </div>
                </div>
                
                <div class="flex items-center text-yellow-400 mb-4">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star text-sm"></i>
                    @endfor
                    <span class="ml-2 text-sm text-gray-600">4.9 ({{ rand(20, 50) }} avis)</span>
                </div>
                
                <button class="w-full border border-primary text-primary px-4 py-2 rounded-lg hover:bg-primary hover:text-white transition-colors text-sm font-medium">
                    <i class="fas fa-envelope mr-2"></i>Contacter l'organisateur
                </button>
            </div>

            <!-- Similar Events -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
                <h4 class="font-bold mb-4">Événements similaires</h4>
                <div class="space-y-3">
                    @for($i = 1; $i <= 3; $i++)
                    <div class="flex space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <img src="https://picsum.photos/60/60?random={{ $i + 10 }}" alt="Event" class="w-15 h-15 object-cover rounded">
                        <div class="flex-1 min-w-0">
                            <h5 class="font-medium text-sm truncate">Événement {{ $event->type }} #{{ $i }}</h5>
                            <p class="text-xs text-gray-600">{{ \Carbon\Carbon::now()->addDays($i * 7)->format('d M') }}</p>
                            <p class="text-xs text-success font-medium">{{ $i * 5 }} DT</p>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Participants Management Modal -->
@if(auth()->check() && auth()->user()->id == $event->user_id)
@include('FrontOffice.Events.partials.participants-modal', ['event' => $event, 'stats' => $stats, 'participantsData' => $participantsData])
@endif

<!-- Delete Confirmation Modal -->
@if(auth()->check() && auth()->user()->id == $event->user_id)
<div id="deleteModal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 opacity-0 pointer-events-none flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <div class="text-center">
            <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Supprimer l'événement ?</h3>
            <p class="text-gray-600 mb-6">
                Cette action est irréversible. Les participants inscrits seront automatiquement notifiés.
            </p>
            
            <div class="flex space-x-3">
                <button onclick="hideDeleteModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Annuler
                </button>
                <form action="{{ route('Events.destroy', $event) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-accent text-white rounded-lg hover:bg-red-600 transition-colors">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Feedback Modal -->
@if($userParticipation && $userParticipation->attendance_status === 'attended')
<div id="feedbackModal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 opacity-0 pointer-events-none flex items-center justify-center transition-all duration-300">
    <div id="feedbackDialog" class="bg-white rounded-2xl max-w-2xl w-full mx-4 transform transition-all duration-300 scale-95">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-star text-warning mr-2"></i>
                    Comment s'est passé l'événement ?
                </h3>
                <button onclick="hideFeedbackModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            @if($userParticipation->feedback)
                <!-- Already submitted feedback -->
                <div class="bg-success-light border border-success rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-success text-2xl mt-1"></i>
                        <div>
                            <h4 class="font-bold text-success mb-2">Merci pour votre avis !</h4>
                            <div class="mb-3">
                                <div class="flex items-center space-x-1 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $userParticipation->rating ? 'text-warning' : 'text-gray-300' }}"></i>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-600">({{ $userParticipation->rating }}/5)</span>
                                </div>
                            </div>
                            <p class="text-gray-700 bg-white p-3 rounded border border-gray-200">
                                {{ $userParticipation->feedback }}
                            </p>
                            @if($userParticipation->sentiment_label)
                                <div class="mt-3 flex items-center space-x-2">
                                    <span class="text-xs text-gray-500">Sentiment analysé:</span>
                                    <span class="px-2 py-1 rounded text-xs font-medium 
                                        {{ $userParticipation->sentiment_label === 'positive' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $userParticipation->sentiment_label === 'negative' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $userParticipation->sentiment_label === 'neutral' ? 'bg-gray-100 text-gray-700' : '' }}">
                                        {{ ucfirst($userParticipation->sentiment_label) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <!-- Feedback Form -->
                <form action="{{ route('Events.feedback.submit', $event->id) }}" method="POST" id="feedbackForm">
                    @csrf
                    
                    <!-- Rating Section -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-star text-warning mr-1"></i>
                            Votre note
                        </label>
                        <div class="flex items-center space-x-2">
                            <div id="starRating" class="flex space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" 
                                            class="star-btn text-3xl text-gray-300 hover:text-warning transition-colors focus:outline-none"
                                            data-rating="{{ $i }}"
                                            onclick="setRating({{ $i }})">
                                        <i class="far fa-star"></i>
                                    </button>
                                @endfor
                            </div>
                            <span id="ratingText" class="text-sm text-gray-600 ml-3"></span>
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" required>
                        <div id="ratingError" class="text-red-500 text-sm mt-1 hidden">Veuillez sélectionner une note</div>
                    </div>

                    <!-- Feedback Text -->
                    <div class="mb-6">
                        <label for="feedbackText" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-comment-dots text-primary mr-1"></i>
                            Votre avis
                        </label>
                        <textarea 
                            name="feedback" 
                            id="feedbackText" 
                            rows="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                            placeholder="Partagez votre expérience avec l'événement... Qu'avez-vous aimé ? Que pourrait-on améliorer ? (Minimum 10 caractères)"
                            minlength="10"
                            maxlength="1000"
                            required
                        ></textarea>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-xs text-gray-500">Minimum 10 caractères, maximum 1000</span>
                            <span id="charCount" class="text-xs text-gray-500">0/1000</span>
                        </div>
                    </div>

                    <!-- AI Note -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-robot text-blue-500 text-xl mt-1"></i>
                            <div class="text-sm text-blue-800">
                                <strong>Intelligence Artificielle :</strong> Votre feedback sera automatiquement analysé par notre IA pour identifier le sentiment et les thèmes clés. Cela aide les organisateurs à améliorer leurs futurs événements ! 🚀
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex space-x-3">
                        <button type="button" 
                                onclick="hideFeedbackModal()" 
                                class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Plus tard
                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-3 bg-primary text-white rounded-lg hover:bg-green-600 transition-colors font-medium">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Envoyer mon avis
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
   // Tab switching
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
        // Remove active from all tabs
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active', 'border-primary', 'text-primary');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        
        // Add active to clicked tab
        button.classList.add('active', 'border-primary', 'text-primary');
        button.classList.remove('border-transparent', 'text-gray-500');
        document.getElementById(button.dataset.tab).classList.add('active');
    });
});

// Enhanced Modal functions with smooth animations
function showDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('show');
}

function hideDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('show');
}

function showParticipantsModal() {
    const modal = document.getElementById('participantsModal');
    const dialog = document.getElementById('participantsDialog');
    
    if (modal) {
        // Show modal with fade in
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Trigger reflow for animation
        modal.offsetHeight;
        
        // Add show class for fade in and scale up
        requestAnimationFrame(() => {
            modal.classList.add('show');
            modal.style.opacity = '1';
            modal.style.pointerEvents = 'auto';
            
            if (dialog) {
                dialog.style.transform = 'scale(1)';
            }
        });
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
        
        // Load participants data
        loadParticipantsData();
    }
}

function hideParticipantsModal() {
    const modal = document.getElementById('participantsModal');
    const dialog = document.getElementById('participantsDialog');
    
    if (modal) {
        // Scale down and fade out
        modal.style.opacity = '0';
        modal.style.pointerEvents = 'none';
        
        if (dialog) {
            dialog.style.transform = 'scale(0.95)';
        }
        
        // Remove from DOM after animation
        setTimeout(() => {
            modal.classList.remove('show', 'flex');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('participantsModal');
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                hideParticipantsModal();
            }
        });
    }
    
    // Close with ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const participantsModal = document.getElementById('participantsModal');
            const emailModal = document.getElementById('emailModalModal');
            const feedbackModal = document.getElementById('feedbackModal');
            
            if (feedbackModal && !feedbackModal.classList.contains('opacity-0')) {
                hideFeedbackModal();
            } else if (emailModal && !emailModal.classList.contains('hidden')) {
                hideEmailModalModal();
            } else if (participantsModal && participantsModal.classList.contains('show')) {
                hideParticipantsModal();
            }
        }
    });
});

// Confirmation functions
function confirmUnregister() {
    return confirm('Êtes-vous sûr de vouloir vous désinscrire de cet événement ?');
}

// Feedback Modal Functions
function showFeedbackModal() {
    const modal = document.getElementById('feedbackModal');
    const dialog = document.getElementById('feedbackDialog');
    
    if (modal && dialog) {
        modal.classList.remove('opacity-0', 'pointer-events-none');
        dialog.classList.remove('scale-95');
        dialog.classList.add('scale-100');
        document.body.style.overflow = 'hidden';
    }
}

function hideFeedbackModal() {
    const modal = document.getElementById('feedbackModal');
    const dialog = document.getElementById('feedbackDialog');
    
    if (modal && dialog) {
        modal.classList.add('opacity-0', 'pointer-events-none');
        dialog.classList.remove('scale-100');
        dialog.classList.add('scale-95');
        document.body.style.overflow = 'auto';
    }
}

// Star Rating System
let currentRating = 0;
const ratingLabels = {
    1: 'Très décevant',
    2: 'Décevant',
    3: 'Moyen',
    4: 'Bien',
    5: 'Excellent !'
};

function setRating(rating) {
    currentRating = rating;
    document.getElementById('ratingInput').value = rating;
    document.getElementById('ratingText').textContent = ratingLabels[rating];
    document.getElementById('ratingError').classList.add('hidden');
    
    // Update star display
    const stars = document.querySelectorAll('.star-btn');
    stars.forEach((star, index) => {
        const icon = star.querySelector('i');
        if (index < rating) {
            icon.classList.remove('far', 'text-gray-300');
            icon.classList.add('fas', 'text-warning');
            star.classList.remove('text-gray-300');
            star.classList.add('text-warning');
        } else {
            icon.classList.remove('fas', 'text-warning');
            icon.classList.add('far', 'text-gray-300');
            star.classList.remove('text-warning');
            star.classList.add('text-gray-300');
        }
    });
}

// Star hover effect
document.addEventListener('DOMContentLoaded', () => {
    const stars = document.querySelectorAll('.star-btn');
    
    stars.forEach((star, index) => {
        star.addEventListener('mouseenter', () => {
            stars.forEach((s, i) => {
                const icon = s.querySelector('i');
                if (i <= index) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    s.classList.add('text-warning');
                } else if (i >= currentRating) {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    s.classList.remove('text-warning');
                }
            });
        });
        
        star.parentElement.addEventListener('mouseleave', () => {
            if (currentRating > 0) {
                setRating(currentRating);
            } else {
                stars.forEach(s => {
                    const icon = s.querySelector('i');
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    s.classList.remove('text-warning');
                    s.classList.add('text-gray-300');
                });
            }
        });
    });
    
    // Character counter for feedback
    const feedbackText = document.getElementById('feedbackText');
    const charCount = document.getElementById('charCount');
    
    if (feedbackText && charCount) {
        feedbackText.addEventListener('input', () => {
            const length = feedbackText.value.length;
            charCount.textContent = `${length}/1000`;
            
            if (length < 10) {
                charCount.classList.add('text-red-500');
                charCount.classList.remove('text-gray-500');
            } else {
                charCount.classList.remove('text-red-500');
                charCount.classList.add('text-gray-500');
            }
        });
    }
    
    // Form validation
    const feedbackForm = document.getElementById('feedbackForm');
    if (feedbackForm) {
        feedbackForm.addEventListener('submit', (e) => {
            if (!currentRating || currentRating === 0) {
                e.preventDefault();
                document.getElementById('ratingError').classList.remove('hidden');
                document.getElementById('starRating').scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }
        });
    }
    
    // Close feedback modal when clicking outside
    const feedbackModal = document.getElementById('feedbackModal');
    if (feedbackModal) {
        feedbackModal.addEventListener('click', (e) => {
            if (e.target === feedbackModal) {
                hideFeedbackModal();
            }
        });
    }
});

// Real-time countdown
@if(\Carbon\Carbon::parse($event->date_start)->isFuture())
function updateCountdown() {
    const eventDate = new Date('{{ $event->date_start }}');
    const now = new Date();
    const diff = eventDate - now;
    
    if (diff > 0) {
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        
        let countdownText = '';
        if (days > 0) {
            countdownText = `${days} jour${days > 1 ? 's' : ''}`;
            if (hours > 0) countdownText += ` et ${hours}h`;
        } else if (hours > 0) {
            countdownText = `${hours} heure${hours > 1 ? 's' : ''}`;
        } else {
            countdownText = 'Bientôt !';
        }
        
        const countdownEl = document.getElementById('countdown');
        if (countdownEl) {
            countdownEl.textContent = countdownText;
        }
    }
}

// Update countdown every minute
updateCountdown();
setInterval(updateCountdown, 60000);
@endif

    // Dark mode detection
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.classList.add('dark');
    }
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
        if (event.matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    });

    // Auto-open participants modal if URL has #participants
    if (window.location.hash === '#participants') {
        setTimeout(() => {
            showParticipantsModal();
        }, 100);
    }
    
    // Debug: Check feedback modal conditions
    @if($userParticipation)
    console.log('User Participation:', {
        status: '{{ $userParticipation->attendance_status }}',
        hasFeedback: {{ $userParticipation->feedback ? 'true' : 'false' }},
        eventEnded: {{ \Carbon\Carbon::parse($event->date_end)->isPast() ? 'true' : 'false' }}
    });
    @endif
    
    // Auto-open feedback modal if user attended but no feedback
    @if($userParticipation && $userParticipation->attendance_status === 'attended' && !$userParticipation->feedback)
    console.log('Auto-opening feedback modal...');
    setTimeout(() => {
        showFeedbackModal();
    }, 1000);
    @endif
    
    // Export payment data to CSV
    function exportPaymentData() {
        @if($isOrganizer && $event->isPaid())
        @php
            $paymentParticipants = $event->participants()
                ->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])
                ->with('user')
                ->get()
                ->map(function($p) {
                    return [
                        'name' => $p->user->name ?? '',
                        'email' => $p->user->email ?? '',
                        'phone' => $p->user->phone ?? '-',
                        'payment_status' => $p->payment_status,
                        'amount_paid' => $p->amount_paid ?? '0',
                        'payment_completed_at' => $p->payment_completed_at ? $p->payment_completed_at->format('Y-m-d H:i:s') : '-',
                        'invoice_number' => $p->invoice_number ?? '-',
                        'registration_date' => $p->registration_date->format('Y-m-d H:i:s')
                    ];
                });
        @endphp
        const participants = @json($paymentParticipants);
        
        // Prepare CSV data
        const headers = ['Nom', 'Email', 'Téléphone', 'Statut Paiement', 'Montant Payé', 'Date Paiement', 'Facture', 'Date Inscription'];
        const rows = participants.map(p => [
            p.name,
            p.email,
            p.phone,
            p.payment_status,
            p.amount_paid,
            p.payment_completed_at,
            p.invoice_number,
            p.registration_date
        ]);
        
        // Create CSV content
        let csvContent = headers.join(',') + '\n';
        rows.forEach(row => {
            csvContent += row.map(cell => `"${cell}"`).join(',') + '\n';
        });
        
        // Download CSV
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', 'paiements_{{ $event->slug }}_{{ date("Y-m-d") }}.csv');
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        @endif
    }
    
    // Quick reject refund function
    function showQuickReject(refundId) {
        const reason = prompt('Raison du rejet de la demande de remboursement:');
        if (reason && reason.trim()) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/events/{{ $event->id }}/refund/${refundId}/reject`;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            
            const reasonInput = document.createElement('input');
            reasonInput.type = 'hidden';
            reasonInput.name = 'rejection_reason';
            reasonInput.value = reason.trim();
            
            form.appendChild(csrfInput);
            form.appendChild(reasonInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush