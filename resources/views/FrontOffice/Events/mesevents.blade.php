@extends('FrontOffice.layout1.app')

@section('title', 'Mes Événements - Waste2Product')

@push('styles')
<style>
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .tab-button.active {
        background-color: #2E7D47;
        color: white;
        border-color: #2E7D47;
    }
    .event-type-workshop { border-left: 4px solid #2E7D47; }
    .event-type-collection { border-left: 4px solid #06D6A0; }
    .event-type-training { border-left: 4px solid #F4A261; }
    .event-type-repair_cafe { border-left: 4px solid #E76F51; }
    
    .badge-workshop { background-color: #2E7D47; }
    .badge-collection { background-color: #06D6A0; }
    .badge-training { background-color: #F4A261; }
    .badge-repair_cafe { background-color: #E76F51; }
    
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-primary to-success text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">Mes Événements</h1>
                <p class="text-lg opacity-90">Gérez vos participations et vos événements créés</p>
            </div>
            <div class="hidden md:block">
                <a href="{{ route('Events.create') }}" class="bg-white text-primary px-6 py-3 rounded-lg hover:bg-gray-100 transition-colors font-medium">
                    <i class="fas fa-plus mr-2"></i>Créer un événement
                </a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Navigation Tabs -->
    <div class="border-b border-gray-200 mb-8">
        <nav class="flex space-x-8">
            <button class="tab-button active py-3 px-1 border-b-2 border-primary font-medium text-primary" data-tab="participations">
                <i class="fas fa-calendar-check mr-2"></i>
                Mes participations
                <span class="ml-2 bg-primary text-white px-2 py-1 rounded-full text-xs">{{ $participations->count() }}</span>
            </button>
            <button class="tab-button py-3 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700" data-tab="organised">
                <i class="fas fa-calendar-plus mr-2"></i>
                Mes événements créés
                <span class="ml-2 bg-gray-300 text-gray-700 px-2 py-1 rounded-full text-xs">{{ $organizedEvents->count() }}</span>
            </button>
        </nav>
    </div>

    <!-- Participations Tab -->
    <div id="participations" class="tab-content active">
        <!-- Filters -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-3">
                <button class="participation-filter active px-4 py-2 rounded-lg bg-primary text-white text-sm font-medium" data-filter="all">
                    Tous ({{ $participations->count() }})
                </button>
                <button class="participation-filter px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 text-sm font-medium" data-filter="upcoming">
                    À venir ({{ $participations->where('date_start', '>', now())->count() }})
                </button>
                <button class="participation-filter px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 text-sm font-medium" data-filter="past">
                    Passés ({{ $participations->where('date_end', '<', now())->count() }})
                </button>
                <button class="participation-filter px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 text-sm font-medium" data-filter="cancelled">
                    Annulés (0)
                </button>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($participations as $event)
            <div class="participation-card card-hover bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 event-type-{{ $event->type }}" 
                 data-status="{{ \Carbon\Carbon::parse($event->date_start)->isFuture() ? 'upcoming' : 'past' }}">
                <div class="relative">
                    <img src="{{ $event->image ?? 'https://picsum.photos/400/200?random=' . $event->id }}" 
                         alt="{{ $event->title }}" 
                         class="w-full h-32 object-cover rounded-t-xl">
                    
                    <!-- Status Badge -->
                    <div class="absolute top-3 right-3">
                        @if(\Carbon\Carbon::parse($event->date_start)->isFuture())
                            @if(\Carbon\Carbon::parse($event->date_start)->isToday())
                                <span class="bg-warning text-black px-3 py-1 rounded-full text-sm font-medium">
                                    Aujourd'hui
                                </span>
                            @elseif(\Carbon\Carbon::parse($event->date_start)->isTomorrow())
                                <span class="bg-accent text-white px-3 py-1 rounded-full text-sm font-medium">
                                    Demain
                                </span>
                            @else
                                <span class="bg-success text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ \Carbon\Carbon::parse($event->date_start)->diffForHumans() }}
                                </span>
                            @endif
                        @else
                            <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                Terminé
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="badge-{{ $event->type }} text-white px-2 py-1 rounded text-xs font-medium">
                            {{ $types[$event->type]['label'] }}
                        </span>
                        @if(\Carbon\Carbon::parse($event->date_end)->isPast())
                            <span class="text-xs text-gray-500">
                                <i class="fas fa-star text-yellow-400"></i>
                                Notez cet événement
                            </span>
                        @endif
                    </div>
                    
                    <h3 class="font-bold text-lg mb-2 line-clamp-2">{{ $event->title }}</h3>
                    
                    <div class="space-y-1 mb-4 text-sm text-gray-600 dark:text-gray-300">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2 w-4"></i>
                            {{ \Carbon\Carbon::parse($event->date_start)->locale('fr')->isoFormat('ddd D MMM, HH:mm') }}
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 w-4"></i>
                            {{ $event->location }}
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('Events.show', $event) }}" class="flex-1 bg-primary text-white px-3 py-2 rounded-lg hover:bg-green-600 transition-colors text-sm font-medium text-center">
                            Voir l'événement
                        </a>
                        
                        @if(\Carbon\Carbon::parse($event->date_start)->isFuture())
                            <form action="{{ route('Events.unregister', $event) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full border border-accent text-accent px-3 py-2 rounded-lg hover:bg-accent hover:text-white transition-colors text-sm font-medium"
                                        onclick="return confirm('Êtes-vous sûr de vouloir vous désinscrire ?')">
                                    Se désinscrire
                                </button>
                            </form>
                        @elseif(\Carbon\Carbon::parse($event->date_end)->isPast())
                            <button class="flex-1 border border-warning text-warning px-3 py-2 rounded-lg hover:bg-warning hover:text-black transition-colors text-sm font-medium">
                                Laisser un avis
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-medium text-gray-500 mb-2">Aucune participation</h3>
                <p class="text-gray-400 mb-4">Vous ne participez à aucun événement pour le moment.</p>
                <a href="{{ route('Events.index') }}" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors">
                    Découvrir les événements
                </a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Organized Events Tab -->
    <div id="organised" class="tab-content">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 bg-primary rounded-lg">
                        <i class="fas fa-calendar-plus text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Événements créés</p>
                        <p class="text-2xl font-bold">{{ $organizedEvents->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 bg-success rounded-lg">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Participants totaux</p>
                        <p class="text-2xl font-bold">{{ $organizedEvents->sum('current_participants') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 bg-warning rounded-lg">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Note moyenne</p>
                        <p class="text-2xl font-bold">4.8</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 bg-secondary rounded-lg">
                        <i class="fas fa-percentage text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Taux de présence</p>
                        <p class="text-2xl font-bold">92%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Event Button -->
        <div class="mb-6 text-center">
            <a href="{{ route('Events.create') }}" class="inline-flex items-center bg-primary text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors font-medium">
                <i class="fas fa-plus mr-2"></i>
                Créer un nouvel événement
            </a>
        </div>

        <!-- Organized Events List -->
        <div class="space-y-4">
            @forelse($organizedEvents as $event)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 event-type-{{ $event->type }}">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <span class="badge-{{ $event->type }} text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $types[$event->type]['label'] }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($event->created_at)->diffForHumans() }}
                                </span>
                            </div>
                            
                            <h3 class="text-xl font-bold mb-2">{{ $event->title }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600 dark:text-gray-300">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    {{ \Carbon\Carbon::parse($event->date_start)->locale('fr')->isoFormat('ddd D MMM, HH:mm') }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    {{ $event->location }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-users mr-2"></i>
                                    {{ $event->current_participants }}/{{ $event->max_participants }} participants
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            @php $progressPercent = $event->max_participants > 0 ? ($event->current_participants / $event->max_participants) * 100 : 0; @endphp
                            <div class="mt-3">
                                <div class="flex justify-between text-xs text-gray-600 dark:text-gray-300 mb-1">
                                    <span>Inscriptions</span>
                                    <span>{{ round($progressPercent) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-success to-primary h-2 rounded-full transition-all duration-500" 
                                         style="width: {{ $progressPercent }}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="mt-4 md:mt-0 md:ml-6 flex flex-wrap gap-2">
                            <a href="{{ route('Events.show', $event) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <i class="fas fa-eye mr-2"></i>Voir
                            </a>
                            
                            <a href="{{ route('Events.edit', $event) }}" class="inline-flex items-center px-3 py-2 border border-secondary text-secondary rounded-lg text-sm font-medium hover:bg-secondary hover:text-white transition-colors">
                                <i class="fas fa-edit mr-2"></i>Modifier
                            </a>
                            
                            <a href="{{ route('Events.participants', $event) }}" class="inline-flex items-center px-3 py-2 border border-primary text-primary rounded-lg text-sm font-medium hover:bg-primary hover:text-white transition-colors">
                                <i class="fas fa-users mr-2"></i>Participants
                            </a>
                            
                            <button onclick="showDuplicateModal({{ $event->id }})" class="inline-flex items-center px-3 py-2 border border-success text-success rounded-lg text-sm font-medium hover:bg-success hover:text-white transition-colors">
                                <i class="fas fa-copy mr-2"></i>Dupliquer
                            </button>
                            
                            <form action="{{ route('Events.destroy', $event) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-accent text-accent rounded-lg text-sm font-medium hover:bg-accent hover:text-white transition-colors"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ? Cette action est irréversible.')">
                                    <i class="fas fa-trash mr-2"></i>Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <i class="fas fa-calendar-plus text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-medium text-gray-500 mb-2">Aucun événement créé</h3>
                <p class="text-gray-400 mb-4">Commencez par créer votre premier événement.</p>
                <a href="{{ route('Events.create') }}" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Créer un événement
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>
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

    // Participation filters
    document.querySelectorAll('.participation-filter').forEach(filter => {
        filter.addEventListener('click', () => {
            // Update active filter
            document.querySelectorAll('.participation-filter').forEach(f => {
                f.classList.remove('active', 'bg-primary', 'text-white');
                f.classList.add('bg-gray-200', 'text-gray-700');
            });
            filter.classList.add('active', 'bg-primary', 'text-white');
            filter.classList.remove('bg-gray-200', 'text-gray-700');
            
            // Filter cards
            const filterType = filter.dataset.filter;
            document.querySelectorAll('.participation-card').forEach(card => {
                if (filterType === 'all') {
                    card.style.display = 'block';
                } else {
                    const cardStatus = card.dataset.status;
                    if (filterType === cardStatus) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        });
    });

    function showDuplicateModal(eventId) {
        if (confirm('Voulez-vous dupliquer cet événement ? Une copie sera créée avec les mêmes informations.')) {
            // Redirect to create page with event ID for duplication
            window.location.href = `{{ route('Events.create') }}?duplicate=${eventId}`;
        }
    }

    // Dark mode detection
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.classList.add('dark');
    }
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
        if (Events.matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    });
</script>
@endpush