@php
    $isFull = $event->current_participants >= $event->max_participants;
    $progressPercent = $event->max_participants > 0 ? ($event->current_participants / $event->max_participants) * 100 : 0;
    $progressColor = $progressPercent < 50 ? 'bg-success' : ($progressPercent < 80 ? 'bg-warning' : 'bg-accent');
    $daysUntil = \Carbon\Carbon::parse($event->date_start)->diffForHumans();
@endphp

<div class="event-card card-hover bg-white shadow-sm border border-gray-200 event-type-{{ $event->type }}">
    <div class="relative">
        <img src="{{ $event->image ?? 'https://picsum.photos/400/200?random=' . $event->id }}" 
             alt="{{ $event->title }}" 
             class="w-full h-48 object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
        
        <!-- Type Badge -->
        <div class="absolute top-3 left-3">
            <span class="badge-{{ $event->type }} text-white px-3 py-1 rounded-full text-sm font-medium">
                {{ $types[$event->type]['label'] }}
            </span>
        </div>
        
        <!-- Status Badge -->
        <div class="absolute top-3 right-3">
            @if($isFull)
                <span class="bg-accent text-white px-3 py-1 rounded-full text-sm font-medium">Complet</span>
            @else
                <span class="bg-success text-white px-3 py-1 rounded-full text-sm font-medium">Disponible</span>
            @endif
        </div>
        
        <!-- Days until -->
        <div class="absolute bottom-3 right-3">
            <span class="bg-white/20 backdrop-blur-sm text-white px-2 py-1 rounded text-sm">
                @if(\Carbon\Carbon::parse($event->date_start)->isPast())
                    Terminé
                @elseif(\Carbon\Carbon::parse($event->date_start)->isToday())
                    Aujourd'hui
                @elseif(\Carbon\Carbon::parse($event->date_start)->isTomorrow())
                    Demain
                @else
                    {{ $daysUntil }}
                @endif
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
                    {{ \Carbon\Carbon::parse($event->date_start)->locale('fr')->isoFormat('ddd D MMM, HH:mm') }}-{{ \Carbon\Carbon::parse($event->date_end)->format('H:i') }}
                </span>
            </div>
            <div class="flex items-center text-gray-600">
                <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                <span class="text-sm">{{ $event->location }}</span>
            </div>
        </div>
        
        <!-- Participants Progress -->
        <div class="mb-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm text-gray-600">
                    <i class="fas fa-users mr-1"></i>
                    {{ $event->current_participants }}/{{ $event->max_participants }} inscrits
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
            Organisé par {{ $event->organizer ?? $event->user->name ?? 'Organisateur' }}
        </div>
        
        <!-- Actions -->
        <div class="flex gap-2">
            @if(!$isFull)
                @auth
                    <form action="{{ route('evenements.register', $event) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors text-sm font-medium">
                            S'inscrire
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="flex-1 bg-primary text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors text-sm font-medium text-center">
                        Connexion pour s'inscrire
                    </a>
                @endauth
            @else
                <button class="flex-1 bg-gray-300 text-gray-500 px-4 py-2 rounded-lg cursor-not-allowed text-sm font-medium" disabled>
                    Complet
                </button>
            @endif
            <a href="{{ route('evenements.show', $event) }}" class="flex-1 bg-gradient-to-r from-primary to-green-600 hover:from-green-600 hover:to-primary text-white text-center py-3 rounded-lg font-medium transition-all transform hover:scale-105 shadow-md">
                Voir détails
            </a>
        </div>
    </div>
</div>