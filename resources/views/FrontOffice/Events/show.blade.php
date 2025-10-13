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
                @if($event->current_participants >= $event->max_participants)
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
                    
                    <h4 class="text-lg font-semibold mb-3">Matériel nécessaire :</h4>
                    <p class="text-gray-700 mb-6">
                        @if($event->type == 'workshop' || $event->type == 'repair_cafe')
                            Apportez vos objets à réparer ou transformer. Outils de base fournis sur place.
                        @else
                            Aucun matériel spécifique requis. Tout sera fourni sur place.
                        @endif
                    </p>
                    
                    <h4 class="text-lg font-semibold mb-3">Niveau requis :</h4>
                    <span class="inline-block bg-success text-white px-3 py-1 rounded-full text-sm">
                        Débutant - Tous niveaux bienvenus
                    </span>
                </div>
            </div>

            <!-- Program Tab -->
            <div id="program" class="tab-content">
                <h3 class="text-xl font-bold mb-6">Programme de l'événement</h3>
                <div class="space-y-4">
                    @php
                        $startTime = \Carbon\Carbon::parse($event->date_start);
                        $endTime = \Carbon\Carbon::parse($event->date_end);
                        $duration = $startTime->diffInMinutes($endTime);
                        $segments = [
                            ['time' => $startTime->format('H:i'), 'title' => 'Accueil et présentation', 'duration' => 30],
                            ['time' => $startTime->addMinutes(30)->format('H:i'), 'title' => 'Activité principale', 'duration' => $duration - 90],
                            ['time' => $endTime->subMinutes(30)->format('H:i'), 'title' => 'Bilan et échanges', 'duration' => 30]
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
                </div>
            </div>

            <!-- Location Tab -->
            <div id="location" class="tab-content">
                <h3 class="text-xl font-bold mb-6">Lieu de l'événement</h3>
                
                <!-- Map Placeholder -->
                <div class="bg-gray-200 h-64 rounded-lg mb-6 flex items-center justify-center">
                    <div class="text-center text-gray-500">
                        <i class="fas fa-map-marked-alt text-4xl mb-2"></i>
                        <p>Carte Google Maps</p>
                        <p class="text-sm">{{ $event->location }}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold mb-3">Adresse complète</h4>
                        <p class="text-gray-700 mb-4">{{ $event->location }}</p>
                        
                        <h4 class="font-semibold mb-3">Instructions d'accès</h4>
                        <p class="text-gray-700">
                            Accessible en transport en commun. Station de métro/bus la plus proche à 5 minutes à pied.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-3">Informations pratiques</h4>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-center">
                                <i class="fas fa-car mr-2 text-primary"></i>
                                Parking disponible
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-wheelchair mr-2 text-primary"></i>
                                Accessible PMR
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-wifi mr-2 text-primary"></i>
                                WiFi gratuit
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Participants Tab -->
            @if(auth()->check() && (auth()->user()->id == $event->user_id || $isParticipant))
            <div id="participants" class="tab-content">
                <h3 class="text-xl font-bold mb-6">Participants inscrits</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @for($i = 1; $i <= min($event->current_participants, 12); $i++)
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
                    
                    @if($event->current_participants > 12)
                    <div class="flex items-center justify-center p-3 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <span class="text-gray-500 text-sm">
                            +{{ $event->current_participants - 12 }} autres participants
                        </span>
                    </div>
                    @endif
                </div>
                
                @if(auth()->user()->id == $event->user_id)
                <div class="mt-6 text-center">
                    <button onclick="showParticipantsModal()" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors font-medium">
                        <i class="fas fa-users mr-2"></i>Gérer tous les participants
                    </button>
                </div>
                @endif
            </div>
            @endif

            <!-- Reviews Tab -->
            @if(\Carbon\Carbon::parse($event->date_end)->isPast())
            <div id="reviews" class="tab-content">
                <h3 class="text-xl font-bold mb-6">Avis des participants</h3>
                
                <div class="mb-6">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="text-3xl font-bold">4.8</div>
                        <div>
                            <div class="flex text-yellow-400 mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                            </div>
                            <div class="text-sm text-gray-600">Basé sur {{ rand(8, 15) }} avis</div>
                        </div>
                    </div>
                </div>
                
                <!-- Sample Reviews -->
                <div class="space-y-4">
                    @for($i = 1; $i <= 3; $i++)
                    <div class="border-b border-gray-200 pb-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center text-white font-bold">
                                {{ chr(64 + $i) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="font-medium">Participant {{ $i }}</span>
                                    <div class="flex text-yellow-400 text-sm">
                                        @for($j = 1; $j <= 5; $j++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-gray-700 text-sm">
                                    Excellent événement ! J'ai beaucoup appris et l'ambiance était très conviviale. Je recommande vivement.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Registration Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-8">
                <!-- Price -->
                <div class="text-center mb-6">
                    @if($event->price == 0)
                        <div class="text-3xl font-bold text-success mb-2">Gratuit</div>
                    @else
                        <div class="text-3xl font-bold text-primary mb-2">{{ number_format($event->price, 2) }} DT</div>
                    @endif
                    <div class="text-sm text-gray-600">Par participant</div>
                </div>

                <!-- Participants Progress -->
                <div class="mb-6">
                    @php
                        $progressPercent = $event->max_participants > 0 ? ($event->current_participants / $event->max_participants) * 100 : 0;
                    @endphp
                    
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">
                            <i class="fas fa-users mr-1"></i>
                            {{ $event->current_participants }}/{{ $event->max_participants }} inscrits
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
                @php $isFull = $event->current_participants >= $event->max_participants; @endphp
                
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
                            <div class="text-success font-medium mb-2">
                                <i class="fas fa-check-circle mr-1"></i>
                                Vous êtes inscrit(e)
                            </div>
                            <div class="text-sm text-gray-600">
                                Confirmation envoyée par email
                            </div>
                        </div>
                        
                        @if(\Carbon\Carbon::parse($event->date_start)->isFuture())
                        <form action="{{ route('Events.unregister', $event) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full border border-accent text-accent px-4 py-3 rounded-lg hover:bg-accent hover:text-white transition-colors font-medium" 
                                    onclick="return confirmUnregister()">
                                <i class="fas fa-user-minus mr-2"></i>Se désinscrire
                            </button>
                        </form>
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
                        <form action="{{ route('Events.register', $event) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-primary text-white px-4 py-3 rounded-lg hover:bg-green-600 transition-colors font-medium">
                                <i class="fas fa-user-plus mr-2"></i>S'inscrire maintenant
                            </button>
                        </form>
                        <p class="text-sm text-gray-600 text-center mt-2">
                            <i class="fas fa-envelope mr-1"></i>
                            Confirmation par email
                        </p>
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
@include('FrontOffice.Events.partials.participants-modal', ['event' => $event])
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

    // Modal functions
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
        if (modal) {
            modal.classList.add('show');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            
            // Load participants data
            loadParticipantsData();
        }
    }

    function hideParticipantsModal() {
        const modal = document.getElementById('participantsModal');
        if (modal) {
            modal.classList.remove('show');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
    }

    // Confirmation functions
    function confirmUnregister() {
        return confirm('Êtes-vous sûr de vouloir vous désinscrire de cet événement ?');
    }

    // Real-time countdown (basic version)
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
</script>
@endpush