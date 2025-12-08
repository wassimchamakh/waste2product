@extends('FrontOffice.layout1.app')

@section('title', 'Tableau de Bord - Waste2Product')

@section('content')
<!-- Welcome Header -->
<section class="gradient-hero text-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">
                    Bonjour {{ Auth::user()->name ?? 'Ahmed' }} ! 👋
                </h1>
                <p class="text-lg opacity-90 mb-4">
                    Prêt à transformer des déchets aujourd'hui ?
                </p>
                <div class="flex items-center space-x-4 text-sm">
                    <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full">
                        🌱 Niveau 7 • Éco-Warrior
                    </span>
                    <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full">
                        📍 Tunis, Tunisie
                    </span>
                </div>
            </div>
            <div class="mt-6 md:mt-0">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('projects.create') }}" class="bg-white text-primary px-6 py-3 rounded-xl font-medium hover:bg-gray-50 transition-colors shadow-md">
                        <i class="fas fa-plus mr-2"></i>Nouveau Projet
                    </a>
                    <a href="{{ route('dechets.create') }}" class="bg-white text-primary px-6 py-3 rounded-xl font-medium hover:bg-gray-50 transition-colors shadow-md border-2 border-white">
                        <i class="fas fa-recycle mr-2"></i>Déclarer Déchet
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Overview -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <!-- Stat 1 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-primary rounded-full flex items-center justify-center mx-auto mb-4 floating-icon shadow-lg">
                    <i class="fas fa-hammer text-white text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-2">{{ $userStats['projects_created'] }}</div>
                <div class="text-sm text-gray-600 font-medium">Projets Créés</div>
            </div>

            <!-- Stat 2 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-secondary rounded-full flex items-center justify-center mx-auto mb-4 floating-icon shadow-lg" style="animation-delay: -0.5s;">
                    <i class="fas fa-recycle text-white text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-2">{{ $userStats['wastes_posted'] }}</div>
                <div class="text-sm text-gray-600 font-medium">Déchets Déclarés</div>
            </div>

            <!-- Stat 3 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-success rounded-full flex items-center justify-center mx-auto mb-4 floating-icon shadow-lg" style="animation-delay: -1s;">
                    <i class="fas fa-leaf text-white text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-2">{{ $userStats['co2_saved'] }}</div>
                <div class="text-sm text-gray-600 font-medium">Kg CO₂ Économisés</div>
            </div>

            <!-- Stat 4 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-accent rounded-full flex items-center justify-center mx-auto mb-4 floating-icon shadow-lg" style="animation-delay: -1.5s;">
                    <i class="fas fa-star text-white text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($userStats['average_rating'], 1) }}</div>
                <div class="text-sm text-gray-600 font-medium">Note Moyenne</div>
                <div class="text-xs text-success mt-2 font-medium">{{ $userStats['reviews_count'] }} évaluations</div>
            </div>
        </div>
    </div>
</section>

<!-- Main Dashboard Content -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Left Column - Main Content -->
            <div class="order-2 lg:order-1 lg:col-span-2 space-y-6 lg:space-y-8">
                <!-- Mes Projets Récents -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-4 sm:p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg sm:text-xl font-semibold text-gray-900">
                                Mes Projets Récents 🔨
                            </h2>
                            <a href="{{ route('projects.my') }}" class="text-primary hover:text-primary/80 text-sm font-medium">Voir tout</a>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        @if($recentProjects->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentProjects as $project)
                            <a href="{{ route('projects.show', $project['id']) }}" class="flex items-center space-x-4 p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                <div class="w-12 h-12 bg-{{ $project['status'] === 'published' ? 'success' : 'secondary' }} rounded-lg flex items-center justify-center">
                                    <i class="fas fa-{{ $project['icon'] }} text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900">{{ $project['title'] }}</h3>
                                    <p class="text-sm text-gray-600">
                                        {{ ucfirst($project['status']) }}
                                        @if($project['rating'] > 0)
                                            • {{ number_format($project['rating'], 1) }}/5 ⭐
                                        @endif
                                    </p>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-500">
                                        <i class="fas fa-heart text-red-400"></i> {{ $project['likes_count'] }}
                                    </span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-hammer text-4xl mb-3 opacity-30"></i>
                            <p>Aucun projet encore</p>
                            <a href="{{ route('projects.create') }}" class="text-primary hover:underline text-sm">Créer votre premier projet</a>
                        </div>
                        @endif
                    </div>
                </div>                <!-- Activité Récente -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-4 sm:p-6 border-b border-gray-200">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900">
                            Activité Récente 📈
                        </h2>
                    </div>
                    <div class="p-4 sm:p-6">
                        @if($recentActivity->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentActivity as $activity)
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-{{ $activity['color'] }} rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-{{ $activity['icon'] }} text-white text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900">
                                        <span class="font-medium">{{ $activity['title'] }}</span> - {{ $activity['description'] }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-chart-line text-4xl mb-3 opacity-30"></i>
                            <p>Aucune activité récente</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="order-1 lg:order-2 space-y-6 lg:space-y-8">
                <!-- Objectifs du Mois -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-4 sm:p-6 border-b border-gray-100">
                        <h2 class="text-base sm:text-lg font-bold text-gray-900 flex items-center">
                            Objectifs {{ now()->translatedFormat('F') }} <span class="ml-2">🎯</span>
                        </h2>
                    </div>
                    <div class="p-4 sm:p-6 space-y-6">
                        @foreach($monthlyGoals as $goal)
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-semibold text-gray-900">{{ $goal['title'] }}</span>
                                <span class="text-sm font-bold text-gray-600">{{ $goal['current'] }}/{{ $goal['target'] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-{{ $goal['color'] }} h-2.5 rounded-full transition-all duration-500" 
                                     style="width: {{ min(100, ($goal['current'] / $goal['target']) * 100) }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Événements Suivis -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-4 sm:p-6 border-b border-gray-200">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">
                            Mes Événements 📅
                        </h2>
                    </div>
                    <div class="p-4 sm:p-6 space-y-4">
                        @if($upcomingEvents->count() > 0)
                            @foreach($upcomingEvents as $event)
                            <a href="{{ route('Events.show', $event->id) }}" class="flex items-start space-x-4 hover:bg-gray-50 p-2 rounded-lg transition-colors">
                                <div class="bg-primary text-white rounded-lg p-2 flex-shrink-0">
                                    <div class="text-center">
                                        <div class="text-xs font-medium">{{ strtoupper($event->date_start->translatedFormat('M')) }}</div>
                                        <div class="text-sm font-bold">{{ $event->date_start->format('d') }}</div>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900 text-sm">{{ $event->title }}</h3>
                                    <p class="text-xs text-gray-600">{{ $event->date_start->format('l H:i') }} • Inscrit</p>
                                </div>
                            </a>
                            @endforeach
                        @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-calendar text-4xl mb-3 opacity-30"></i>
                            <p>Aucun événement à venir</p>
                            <a href="{{ route('Events.index') }}" class="text-primary hover:underline text-sm">Voir les événements</a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Badges & Récompenses -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-4 sm:p-6 border-b border-gray-200">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">
                            Mes Badges 🏆
                        </h2>
                    </div>
                    <div class="p-4 sm:p-6">
                        @if($badges->count() > 0)
                        <div class="grid grid-cols-3 gap-4">
                            @foreach($badges as $badge)
                            <div class="text-center">
                                <div class="w-12 h-12 bg-{{ $badge['color'] }} rounded-full flex items-center justify-center mx-auto mb-2 text-xl">
                                    {{ $badge['icon'] }}
                                </div>
                                <div class="text-xs font-medium text-gray-900">{{ $badge['name'] }}</div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-trophy text-4xl mb-3 opacity-30"></i>
                            <p class="text-xs">Continuez à contribuer pour gagner des badges !</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Recommandations -->
                <div class="bg-gradient-to-br from-primary to-success text-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-4 sm:p-6">
                        <h2 class="text-lg font-semibold mb-4">
                            Recommandé pour vous 💡
                        </h2>
                        @if($recommendations->count() > 0)
                        <div class="space-y-3">
                            @foreach($recommendations as $rec)
                            <a href="{{ $rec['route'] }}" class="block bg-white bg-opacity-20 rounded-lg p-3 hover:bg-opacity-30 transition-colors">
                                <h3 class="font-medium text-sm mb-1">{{ $rec['title'] }}</h3>
                                <p class="text-xs opacity-90">{{ $rec['description'] }}</p>
                            </a>
                            @endforeach
                        </div>
                        @else
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                            <p class="text-sm">Explorez plus de projets pour recevoir des recommandations personnalisées</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Animate progress bars on scroll
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px'
    };

    const progressObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const progressBars = entry.target.querySelectorAll('[style*="width"]');
                progressBars.forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => {
                        bar.style.transition = 'width 1s ease-out';
                        bar.style.width = width;
                    }, 200);
                });
            }
        });
    }, observerOptions);

    document.querySelectorAll('.bg-white').forEach(section => {
        progressObserver.observe(section);
    });

    // Add hover effects to project cards
    document.querySelectorAll('.bg-gray-50.dark\\:bg-gray-700').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(4px)';
            this.style.transition = 'transform 0.2s ease';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });

    // Simulate real-time updates
    function updateStats() {
        const statsElements = document.querySelectorAll('[class*="text-2xl font-bold"]');
        statsElements.forEach((stat, index) => {
            const currentValue = parseInt(stat.textContent);
            if (Math.random() > 0.95) { // 5% chance to update
                stat.style.transform = 'scale(1.1)';
                stat.style.transition = 'transform 0.3s ease';
                setTimeout(() => {
                    stat.style.transform = 'scale(1)';
                }, 300);
            }
        });
    }

    // Update stats every 10 seconds
    setInterval(updateStats, 10000);
</script>
@endpush