@extends('FrontOffice.layout.app')

@section('title', 'Accueil - Waste2Product')

@section('content')

<!-- Main Content -->
<main class="pt-16">
    <!-- Hero Section -->
    <section id="home" class="relative gradient-hero text-white py-24 overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 w-72 h-72 bg-white opacity-5 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-secondary opacity-5 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <!-- Hero Badge -->
                <div class="inline-flex items-center gap-2 bg-white bg-opacity-20 backdrop-blur-md rounded-full px-6 py-2 mb-6 animate-fade-in">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-success opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-success"></span>
                    </span>
                    <span class="text-sm font-medium">+{{ $stats['users_count'] }} membres actifs • Tunisie</span>
                </div>

                <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                    Transformons les déchets
                    <span class="block bg-gradient-to-r from-secondary via-accent to-yellow-300 bg-clip-text text-transparent animate-gradient">
                        en ressources précieuses
                    </span>
                </h1>

                <p class="text-xl md:text-2xl mb-10 max-w-3xl mx-auto opacity-90 leading-relaxed">
                    Rejoignez la première plateforme tunisienne d'économie circulaire.
                    <span class="font-semibold text-secondary">Donnez, transformez, valorisez</span> vos déchets pour un avenir plus vert 🌱
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                    <a href="{{ route('dechets.index') }}" class="group relative inline-flex items-center justify-center gap-3 bg-white text-primary hover:bg-gray-50 px-8 py-4 rounded-xl font-bold text-lg shadow-2xl hover:shadow-3xl transition-all transform hover:-translate-y-1">
                        <i class="fas fa-search"></i>
                        <span>Explorer les déchets</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>

                    <a href="{{ route('dechets.create') }}" class="group inline-flex items-center justify-center gap-3 bg-gradient-to-r from-secondary to-accent hover:from-accent hover:to-secondary text-white px-8 py-4 rounded-xl font-bold text-lg shadow-2xl hover:shadow-3xl transition-all transform hover:-translate-y-1">
                        <i class="fas fa-plus-circle"></i>
                        <span>Déclarer un déchet</span>
                    </a>
                </div>

                <!-- Trust Indicators -->
                <div class="flex flex-wrap items-center justify-center gap-6 text-sm opacity-80">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-shield-alt text-secondary"></i>
                        <span>100% Gratuit</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-users text-secondary"></i>
                        <span>Communauté Vérifiée</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-leaf text-secondary"></i>
                        <span>Impact Environnemental</span>
                    </div>
                </div>
            </div>
            
            <!-- Impact Statistics -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-5xl mx-auto">
                <div class="group bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-6 hover:bg-opacity-20 transition-all transform hover:scale-105 border border-white border-opacity-20">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-success rounded-full mb-3">
                            <i class="fas fa-leaf text-white text-xl"></i>
                        </div>
                        <div class="counter-animation">
                            <div class="text-4xl md:text-5xl font-bold mb-1">{{ number_format($stats['co2_saved']) }}</div>
                            <div class="text-sm opacity-80 font-medium">Kg CO₂ économisés</div>
                        </div>
                    </div>
                </div>
                <div class="group bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-6 hover:bg-opacity-20 transition-all transform hover:scale-105 border border-white border-opacity-20">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-secondary rounded-full mb-3">
                            <i class="fas fa-project-diagram text-white text-xl"></i>
                        </div>
                        <div class="counter-animation">
                            <div class="text-4xl md:text-5xl font-bold mb-1">{{ $stats['projects_count'] }}</div>
                            <div class="text-sm opacity-80 font-medium">Projets réalisés</div>
                        </div>
                    </div>
                </div>
                <div class="group bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-6 hover:bg-opacity-20 transition-all transform hover:scale-105 border border-white border-opacity-20">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-accent rounded-full mb-3">
                            <i class="fas fa-recycle text-white text-xl"></i>
                        </div>
                        <div class="counter-animation">
                            <div class="text-4xl md:text-5xl font-bold mb-1">{{ $stats['wastes_count'] }}</div>
                            <div class="text-sm opacity-80 font-medium">Déchets valorisés</div>
                        </div>
                    </div>
                </div>
                <div class="group bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-6 hover:bg-opacity-20 transition-all transform hover:scale-105 border border-white border-opacity-20">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-500 rounded-full mb-3">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <div class="counter-animation">
                            <div class="text-4xl md:text-5xl font-bold mb-1">{{ $stats['users_count'] }}</div>
                            <div class="text-sm opacity-80 font-medium">Membres actifs</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Projects Section -->
    <section id="projets" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-3">
                        Projets populaires 🔥
                    </h2>
                    <p class="text-gray-600 max-w-2xl">
                        Découvrez les créations les plus appréciées par notre communauté tunisienne
                    </p>
                </div>
                <a href="{{ route('projects.index') }}" class="hidden md:inline-flex items-center gap-2 bg-primary hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105">
                    Voir tout
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($popularProjects as $project)
                <!-- Project Card -->
                <div class="group relative card-hover bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-300">
                    <!-- Image/Icon Section with Overlay -->
                    <div class="relative h-56 bg-gradient-to-br from-primary via-green-600 to-success flex items-center justify-center overflow-hidden">
                        @if($project['image'])
                            <img src="{{ $project['image'] }}" alt="{{ $project['title'] }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                        @else
                            <i class="fas fa-{{ $project['icon'] }} text-white text-5xl transform group-hover:scale-110 transition-transform duration-300 relative z-10"></i>
                        @endif
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity"></div>

                        <!-- Difficulty Badge -->
                        <span class="absolute top-4 left-4 bg-{{ $project['difficulty_color'] }} text-white px-4 py-1.5 rounded-full text-xs font-bold shadow-lg z-20">
                            {{ $project['difficulty'] }}
                        </span>

                        <!-- Duration Badge -->
                        <span class="absolute top-4 right-4 bg-white bg-opacity-90 text-gray-800 px-3 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1 shadow-lg z-20">
                            <i class="fas fa-clock text-primary"></i>
                            {{ $project['duration'] }}h
                        </span>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-primary transition-colors">
                            {{ $project['title'] }}
                        </h3>

                        <!-- Creator Info -->
                        <div class="flex items-center gap-2 mb-4 text-sm text-gray-600">
                            <div class="w-8 h-8 bg-gradient-to-br from-primary to-green-600 rounded-full flex items-center justify-center text-white font-bold text-xs">
                                {{ strtoupper(substr($project['creator'], 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium">{{ $project['creator'] }}</p>
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-map-marker-alt text-accent"></i> {{ $project['city'] }}
                                </p>
                            </div>
                        </div>

                        <!-- Rating & CTA -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center gap-1.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= floor($project['rating']) ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                @endfor
                                <span class="text-sm font-semibold text-gray-700 ml-1">
                                    {{ $project['rating'] }}
                                </span>
                                <span class="text-xs text-gray-500">({{ $project['reviews_count'] }})</span>
                            </div>
                            <a href="{{ route('projects.show', $project['id']) }}" class="inline-flex items-center gap-1 text-primary hover:text-green-700 font-semibold text-sm group-hover:gap-2 transition-all">
                                Voir
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
           
        </div>
    </section>

    <!-- Recent Wastes Section -->
    <section id="dechets" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-3">
                        Déchets récents ♻️
                    </h2>
                    <p class="text-gray-600 max-w-2xl">
                        Trouvez des matériaux près de chez vous pour vos prochains projets
                    </p>
                </div>
                <a href="{{ route('dechets.index') }}" class="hidden md:inline-flex items-center gap-2 bg-primary hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105">
                    Voir tout
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($recentWastes as $waste)
                <!-- Waste Item -->
                <div class="group card-hover bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="relative h-40 bg-gradient-to-br from-primary via-green-500 to-teal-500 flex items-center justify-center overflow-hidden">
                        @if($waste['image'])
                            <img src="{{ $waste['image'] }}" alt="{{ $waste['title'] }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-125 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/20 to-transparent"></div>
                        @else
                            <i class="fas fa-{{ $waste['icon'] }} text-white text-3xl transform group-hover:scale-125 group-hover:rotate-12 transition-all duration-300 relative z-10"></i>
                        @endif
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity"></div>

                        <!-- Available Badge -->
                        <span class="absolute top-3 right-3 bg-success text-white px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1 shadow-lg z-20">
                            <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                            Disponible
                        </span>
                    </div>
                    <div class="p-5">
                        <h4 class="font-bold text-gray-900 mb-2 group-hover:text-primary transition-colors">{{ $waste['title'] }}</h4>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt text-accent"></i>
                                <span class="font-medium">{{ $waste['location'] }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <i class="fas fa-clock"></i>
                                <span>{{ $waste['time_ago'] }}</span>
                            </div>
                        </div>

                        <a href="{{ route('dechets.show', $waste['id']) }}" class="block w-full bg-gradient-to-r from-primary to-green-600 hover:from-green-600 hover:to-primary text-white text-center text-sm font-semibold py-2.5 rounded-lg shadow-md transition-all transform hover:scale-105">
                            Voir détails
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
          
        </div>
    </section>

    <!-- Upcoming Events Section -->
    <section id="evenements" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Événements à venir 📅
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Participez aux ateliers et événements de votre région
                </p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @foreach($upcomingEvents as $event)
                <!-- Event -->
                <div class="card-hover bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-start space-x-4">
                        <div class="bg-{{ $event['color'] }} text-white rounded-lg p-3 flex-shrink-0">
                            <div class="text-center">
                                <div class="text-xs font-medium">{{ $event['month'] }}</div>
                                <div class="text-lg font-bold">{{ $event['date'] }}</div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                {{ $event['title'] }}
                            </h3>
                            <p class="text-sm text-gray-600 mb-3">
                                {{ $event['time_location'] }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs bg-success text-white px-2 py-1 rounded-full">
                                    {{ $event['available_spots'] }} places
                                </span>
                                <a href="{{ route('Events.show', $event['id']) }}" class="bg-primary hover:bg-primary/90 text-white text-sm px-4 py-1 rounded-lg font-medium transition-colors">S'inscrire</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
           
        </div>
    </section>

    <!-- Community Testimonials -->
    <section id="tutoriels" class="py-16 bg-gradient-to-r from-primary to-success text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">
                    Témoignages communauté 💬
                </h2>
                <p class="text-xl opacity-90 max-w-2xl mx-auto">
                    Découvrez les transformations inspirantes de nos membres
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($testimonials as $testimonial)
                <!-- Testimonial -->
                <div class="bg-white bg-opacity-10 rounded-xl p-6 backdrop-blur">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">{{ $testimonial['name'] }}</h4>
                            <p class="text-sm opacity-80">{{ $testimonial['location'] }}</p>
                        </div>
                    </div>
                    <p class="text-lg mb-4">
                        "{{ $testimonial['content'] }}"
                    </p>
                    <div class="flex items-center">
                        <div class="flex text-yellow-400 mr-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $testimonial['rating'] ? '' : '-o' }}"></i>
                            @endfor
                        </div>
                        <span class="text-sm opacity-80">{{ $testimonial['rating'] }}/5</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Call to Actions -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- CTA 1 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4 floating-icon">
                        <i class="fas fa-plus text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">
                        Déclarer un déchet
                    </h3>
                    <p class="text-gray-600 mb-6">
                        Partagez vos matériaux inutilisés avec la communauté
                    </p>
                    <a href="/dechets/nouveau" class="btn-primary px-6 py-3">Commencer 🚀</a>
                </div>
                
                <!-- CTA 2 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-secondary rounded-full flex items-center justify-center mx-auto mb-4 floating-icon" style="animation-delay: -1s;">
                        <i class="fas fa-lightbulb text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">
                        Créer un projet
                    </h3>
                    <p class="text-gray-600 mb-6">
                        Partagez vos idées créatives de transformation
                    </p>
                    <a href="/projets/nouveau" class="btn-secondary px-6 py-3">Créer 💡</a>
                </div>
                
                <!-- CTA 3 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-success rounded-full flex items-center justify-center mx-auto mb-4 floating-icon" style="animation-delay: -2s;">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">
                        Rejoindre la communauté
                    </h3>
                    <p class="text-gray-600 mb-6">
                        Connectez-vous avec d'autres passionnés
                    </p>
                    <a href="/register" class="btn-accent px-6 py-3">Rejoindre 🤝</a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('scripts')
// Counter animation
function animateCounters() {
    const counters = document.querySelectorAll('.counter-animation');
    counters.forEach(counter => {
        const target = parseInt(counter.querySelector('div').textContent.replace(/,/g, ''));
        let current = 0;
        const increment = target / 100;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            counter.querySelector('div').textContent = Math.floor(current).toLocaleString();
        }, 20);
    });
}

// Intersection Observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-fade-in');
            
            if (entry.target.querySelector('.counter-animation')) {
                animateCounters();
            }
        }
    });
}, observerOptions);

document.querySelectorAll('section').forEach(section => {
    observer.observe(section);
});
@endsection