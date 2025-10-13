@extends('FrontOffice.layout1.app')

@section('title', 'Événements Communautaires - Waste2Product')

@push('styles')
<style>
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
    }
    
    .floating-icon {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .progress-bar {
        transition: width 1s ease-in-out;
    }
    
    .calendar-day {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .calendar-day:hover {
        background-color: #2E7D47 !important;
        color: white !important;
    }
    
    .event-type-workshop { border-left: 4px solid #2E7D47; }
    .event-type-collection { border-left: 4px solid #06D6A0; }
    .event-type-training { border-left: 4px solid #F4A261; }
    .event-type-repair_cafe { border-left: 4px solid #E76F51; }
    
    .badge-workshop { background-color: #2E7D47; }
    .badge-collection { background-color: #06D6A0; }
    .badge-training { background-color: #F4A261; }
    .badge-repair_cafe { background-color: #E76F51; }
    
    .view-section { display: none; }
    .view-section.active { display: block; }
    
    .tab-btn.active {
        background-color: #2E7D47 !important;
        color: white !important;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary to-success text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="floating-icon mb-6">
            <i class="fas fa-calendar-alt text-6xl opacity-80"></i>
        </div>
        <h1 class="text-4xl md:text-6xl font-bold mb-4">
            Événements Communautaires
        </h1>
        <p class="text-xl md:text-2xl mb-8 opacity-90">
            Rejoignez notre mouvement pour une économie circulaire en Tunisie
        </p>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                <div class="text-3xl font-bold">{{ $stats['events_this_month'] }}</div>
                <div class="text-sm opacity-80">Événements ce mois</div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                <div class="text-3xl font-bold">{{ $stats['total_participants'] }}</div>
                <div class="text-sm opacity-80">Participants totaux</div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                <div class="text-3xl font-bold">{{ $stats['upcoming_events'] }}</div>
                <div class="text-sm opacity-80">Prochains événements</div>
            </div>
        </div>
    </div>
</section>

<!-- Navigation Tabs -->
<section class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex space-x-4 py-4">
            <button class="tab-btn active px-4 py-2 text-sm font-medium rounded-lg transition-all" data-view="list">
                <i class="fas fa-list mr-2"></i>
                Vue Liste
            </button>
            <button class="tab-btn px-4 py-2 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 transition-all" data-view="calendar">
                <i class="fas fa-calendar-alt mr-2"></i>
                Vue Calendrier
            </button>
            <button class="tab-btn px-4 py-2 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 transition-all" data-view="popular">
                <i class="fas fa-fire mr-2"></i>
                Événements Populaires
            </button>
        </nav>
    </div>
</section>

<!-- Filters -->
<section class="bg-white border-b border-gray-200 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('Events.index') }}" id="filters-form">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <div class="relative">
                        <input type="text" name="search" id="search-input" placeholder="Rechercher un événement..." 
                            value="{{ request('search') }}"
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>
                
                <!-- Type Filter -->
                <div>
                    <select name="type" id="filter-type" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        <option value="">Tous les types</option>
                        @foreach($types as $key => $type)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                {{ $type['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- City Filter -->
                <div>
                    <select name="city" id="filter-city" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        <option value="">Toutes les villes</option>
                        @foreach($cities as $cityKey => $cityName)
                            <option value="{{ $cityKey }}" {{ request('city') == $cityKey ? 'selected' : '' }}>
                                {{ $cityName }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Additional Filters -->
            <div class="flex flex-wrap items-center gap-4">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="free_only" value="1" id="filter-free" 
                        {{ request('free_only') ? 'checked' : '' }}
                        class="mr-2 text-primary focus:ring-primary rounded">
                    <span class="text-sm">Gratuit uniquement</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="hide_full" value="1" id="filter-hide-full" 
                        {{ request('hide_full') ? 'checked' : '' }}
                        class="mr-2 text-primary focus:ring-primary rounded">
                    <span class="text-sm">Masquer les complets</span>
                </label>
                <button type="button" id="reset-filters" class="text-sm text-primary hover:text-green-700">
                    <i class="fas fa-redo mr-1"></i>Réinitialiser
                </button>
                <div class="ml-auto text-sm text-gray-600">
                    <span id="results-count">{{ $events->count() }} événements trouvés</span>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- List View -->
<section id="list-view" class="view-section active py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="events-grid">
            @forelse($events as $event)
                @php
                    $currentParticipants = $event->participants->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])->count();
                    $isFull = $currentParticipants >= $event->max_participants;
                    $progressPercent = $event->max_participants > 0 ? ($currentParticipants / $event->max_participants) * 100 : 0;
                    $progressColor = $progressPercent < 50 ? 'bg-success' : ($progressPercent < 80 ? 'bg-warning' : 'bg-accent');
                @endphp

                <div class="event-card card-hover bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden event-type-{{ $event->type }}">
                    <div class="relative">
                        <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://picsum.photos/400/200?random=' . $event->id }}" 
                             alt="{{ $event->title }}" 
                             class="w-full h-48 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        
                        <!-- Type Badge -->
                        <div class="absolute top-3 left-3">
                            <span class="badge-{{ $event->type }} text-white px-3 py-1 rounded-full text-sm font-medium shadow-lg">
                                {{ $types[$event->type]['label'] }}
                            </span>
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            @if($isFull)
                                <span class="bg-accent text-white px-3 py-1 rounded-full text-sm font-medium shadow-lg">Complet</span>
                            @else
                                <span class="bg-success text-white px-3 py-1 rounded-full text-sm font-medium shadow-lg">Disponible</span>
                            @endif
                        </div>
                        
                        <!-- Countdown -->
                        <div class="absolute bottom-3 right-3">
                            <span class="bg-white/20 backdrop-blur-sm text-white px-2 py-1 rounded text-sm">
                                {{ $event->countdown }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                            {{ $event->title }}
                        </h3>
                        
                        <!-- Date and Location -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-calendar-alt mr-2 text-primary"></i>
                                <span class="text-sm">
                                    {{ $event->date_start->locale('fr')->isoFormat('ddd D MMM, HH:mm') }}-{{ $event->date_end->format('H:i') }}
                                </span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                                <span class="text-sm line-clamp-1">{{ $event->location }}</span>
                            </div>
                        </div>
                        
                        <!-- Participants Progress -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">
                                    <i class="fas fa-users mr-1"></i>
                                    {{ $currentParticipants }}/{{ $event->max_participants }} inscrits
                                </span>
                                <span class="text-sm font-medium">{{ round($progressPercent) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="progress-bar {{ $progressColor }} h-2 rounded-full" style="width: {{ $progressPercent }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Price -->
                        <div class="mb-4">
                            @if($event->price == 0)
                                <span class="bg-success text-white px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-tag mr-1"></i>Gratuit
                                </span>
                            @else
                                <span class="bg-secondary text-white px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-tag mr-1"></i>{{ number_format($event->price, 2) }} DT
                                </span>
                            @endif
                        </div>
                        
                        <!-- Organizer -->
                        <div class="mb-4 text-sm text-gray-600">
                            <i class="fas fa-user mr-1"></i>
                            Organisé par {{ $event->user->name ?? 'Organisateur' }}
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex gap-2">
                            @if(!$isFull)
                                <form action="{{ route('Events.register', $event->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors text-sm font-medium">
                                        S'inscrire
                                    </button>
                                </form>
                            @else
                                <button class="flex-1 bg-gray-300 text-gray-500 px-4 py-2 rounded-lg cursor-not-allowed text-sm font-medium" disabled>
                                    Complet
                                </button>
                            @endif
                            <a href="{{ route('Events.show', $event->id) }}" class="flex-1 border border-primary text-primary px-4 py-2 rounded-lg hover:bg-primary hover:text-white transition-colors text-sm font-medium text-center">
                                Détails
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-500 mb-2">Aucun événement trouvé</h3>
                    <p class="text-gray-400 mb-4">Modifiez vos filtres ou créez un nouvel événement.</p>
                    <a href="{{ route('Events.create') }}" class="inline-block bg-primary text-white px-6 py-3 rounded-lg hover:bg-green-600">
                        Créer un événement
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Calendar View -->
<section id="calendar-view" class="view-section py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- Calendar Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900" id="calendar-month"></h2>
                <div class="flex space-x-2">
                    <button id="prev-month" class="p-2 text-gray-400 hover:text-primary rounded-lg hover:bg-gray-100">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button id="next-month" class="p-2 text-gray-400 hover:text-primary rounded-lg hover:bg-gray-100">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            
            <!-- Calendar Grid -->
            <div class="p-6">
                <!-- Days of week -->
                <div class="grid grid-cols-7 gap-2 mb-4">
                    @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $day)
                        <div class="p-3 text-center text-sm font-medium text-gray-500">{{ $day }}</div>
                    @endforeach
                </div>
                
                <!-- Calendar days -->
                <div class="grid grid-cols-7 gap-2" id="calendar-days"></div>
            </div>
        </div>
    </div>
</section>

<!-- Popular View -->
<section id="popular-view" class="view-section py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Événements Populaires</h2>
            <p class="text-gray-600">Les événements avec le plus fort taux de remplissage</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($popularEvents as $event)
                @php
                    $currentParticipants = $event->participants->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])->count();
                    $progressPercent = $event->max_participants > 0 ? ($currentParticipants / $event->max_participants) * 100 : 0;
                @endphp
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                    <div class="flex items-start justify-between mb-4">
                        <span class="badge-{{ $event->type }} text-white px-3 py-1 rounded-full text-sm">
                            {{ $types[$event->type]['label'] }}
                        </span>
                        <span class="text-2xl font-bold text-primary">{{ round($progressPercent) }}%</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $event->title }}</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        <i class="fas fa-users mr-1"></i>
                        {{ $currentParticipants }}/{{ $event->max_participants }} inscrits
                    </p>
                    <a href="{{ route('Events.show', $event->id) }}" class="text-primary hover:text-green-700 text-sm font-medium">
                        Voir l'événement <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Flash Messages -->
@if(session('success'))
<div class="fixed top-20 right-4 z-50 bg-success text-white p-4 rounded-lg shadow-lg animate-fade-in" id="flash-success">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <span>{{ session('success') }}</span>
        <button class="ml-4 hover:opacity-75" onclick="document.getElementById('flash-success').remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
@endif

@if(session('error'))
<div class="fixed top-20 right-4 z-50 bg-accent text-white p-4 rounded-lg shadow-lg animate-fade-in" id="flash-error">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <span>{{ session('error') }}</span>
        <button class="ml-4 hover:opacity-75" onclick="document.getElementById('flash-error').remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
@endif

<!-- Create Event CTA -->
<div class="fixed bottom-6 right-6 z-50">
    <a href="{{ route('Events.create') }}" class="bg-primary text-white px-6 py-3 rounded-full shadow-lg hover:bg-green-600 transform hover:scale-105 transition-all duration-200 flex items-center">
        <i class="fas fa-plus mr-2"></i>
        <span class="hidden md:inline">Organiser un événement</span>
    </a>
</div>
@endsection

@push('scripts')
<script>
    // Events data from Laravel
    const eventsData = {!! json_encode($events->map(function($event) {
        return [
            'id' => $event->id,
            'title' => $event->title,
            'type' => $event->type,
            'date_start' => $event->date_start->format('Y-m-d H:i:s'),
            'date_end' => $event->date_end->format('Y-m-d H:i:s'),
            'location' => $event->location,
        ];
    })->toArray()) !!};    
    // Current state
    let currentView = 'list';
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();

    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const view = this.dataset.view;
            
            // Update tabs
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Show/hide views
            document.querySelectorAll('.view-section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(view + '-view').classList.add('active');
            
            currentView = view;
            
            if (view === 'calendar') {
                renderCalendar();
            }
        });
    });

    // Auto-submit filters
    document.querySelectorAll('#filter-type, #filter-city, #filter-free, #filter-hide-full').forEach(input => {
        input.addEventListener('change', () => {
            document.getElementById('filters-form').submit();
        });
    });

    // Search with debounce
    let searchTimeout;
    document.getElementById('search-input').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filters-form').submit();
        }, 500);
    });

    // Reset filters
    document.getElementById('reset-filters').addEventListener('click', () => {
        window.location.href = '{{ route("Events.index") }}';
    });

    // Calendar rendering
    function renderCalendar() {
        const monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                           'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        
        document.getElementById('calendar-month').textContent = `${monthNames[currentMonth]} ${currentYear}`;
        
        const firstDay = new Date(currentYear, currentMonth, 1);
        const lastDay = new Date(currentYear, currentMonth + 1, 0);
        const startDate = new Date(firstDay);
        const dayOfWeek = firstDay.getDay();
        const offset = dayOfWeek === 0 ? 6 : dayOfWeek - 1;
        startDate.setDate(startDate.getDate() - offset);
        
        const calendarDays = document.getElementById('calendar-days');
        calendarDays.innerHTML = '';
        
        for (let i = 0; i < 42; i++) {
            const day = new Date(startDate);
            day.setDate(startDate.getDate() + i);
            
            const isCurrentMonth = day.getMonth() === currentMonth;
            const isToday = day.toDateString() === new Date().toDateString();
            
            const dayEvents = eventsData.filter(event => {
                const eventDate = new Date(event.date_start);
                return eventDate.toDateString() === day.toDateString();
            });
            
            const dayElement = document.createElement('div');
            dayElement.className = `calendar-day p-2 min-h-[80px] border rounded-lg ${
                isCurrentMonth ? 'bg-white border-gray-200' : 
                'bg-gray-50 text-gray-400 border-gray-100'
            } ${isToday ? '!bg-primary text-white border-primary' : ''}`;
            
            dayElement.innerHTML = `
                <div class="text-sm font-medium mb-1">${day.getDate()}</div>
                ${dayEvents.slice(0, 2).map(event => `
                    <div class="text-xs p-1 rounded mb-1 badge-${event.type} text-white truncate">
                        ${event.title}
                    </div>
                `).join('')}
                ${dayEvents.length > 2 ? `<div class="text-xs opacity-70">+${dayEvents.length - 2}</div>` : ''}
            `;
            
            calendarDays.appendChild(dayElement);
        }
    }

    // Calendar navigation
    document.getElementById('prev-month').addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar();
    });

    document.getElementById('next-month').addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar();
    });

    // Auto-hide flash messages
    setTimeout(() => {
        const flashSuccess = document.getElementById('flash-success');
        const flashError = document.getElementById('flash-error');
        if (flashSuccess) flashSuccess.remove();
        if (flashError) flashError.remove();
    }, 5000);
    
    // Initialize calendar if needed
    document.addEventListener('DOMContentLoaded', () => {
        if (currentView === 'calendar') {
            renderCalendar();
        }
    });
</script>
@endpush