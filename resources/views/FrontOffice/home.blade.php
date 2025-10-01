@extends('FrontOffice.layout.app')

@section('title', 'Accueil - Waste2Product')

@section('content')
{{-- Données statiques simulées --}}
@php
    $stats = [
        'co2_saved' => 2847,
        'projects_count' => 156,
        'wastes_count' => 89,
        'users_count' => 423,
    ];

    $popularProjects = [
        [
            'title' => 'Table Basse Palettes Industrielle',
            'difficulty' => 'Moyen',
            'difficulty_color' => 'secondary',
            'duration' => '6',
            'creator' => 'Ahmed Ben Salem',
            'city' => 'Tunis',
            'rating' => 4.8,
            'reviews_count' => 24,
            'icon' => 'table'
        ],
        [
            'title' => 'Jardinière Suspendue Bouteilles',
            'difficulty' => 'Facile',
            'difficulty_color' => 'success',
            'duration' => '2',
            'creator' => 'Sarah Mansouri',
            'city' => 'Sfax',
            'rating' => 4.9,
            'reviews_count' => 31,
            'icon' => 'seedling'
        ],
        [
            'title' => 'Luminaire Créatif Canettes',
            'difficulty' => 'Moyen',
            'difficulty_color' => 'secondary',
            'duration' => '4',
            'creator' => 'Youssef Trabelsi',
            'city' => 'Sousse',
            'rating' => 4.7,
            'reviews_count' => 18,
            'icon' => 'lightbulb'
        ]
    ];

    $recentWastes = [
        [
            'title' => 'Palettes Européennes',
            'location' => 'Tunis, Ben Arous',
            'time_ago' => 'Déclaré il y a 2h',
            'icon' => 'pallet'
        ],
        [
            'title' => 'Bouteilles Plastique',
            'location' => 'Sfax',
            'time_ago' => 'Lot de 50 • 1.5L',
            'icon' => 'wine-bottle'
        ],
        [
            'title' => 'Caisses de Vin',
            'location' => 'Nabeul',
            'time_ago' => 'Cave vinicole • 12 unités',
            'icon' => 'box'
        ],
        [
            'title' => 'Pneus Usagés',
            'location' => 'Sousse',
            'time_ago' => 'Garage • Diverses tailles',
            'icon' => 'tire'
        ]
    ];

    $upcomingEvents = [
        [
            'title' => 'Repair Café Tunis 🔧',
            'date' => '15',
            'month' => 'DÉC',
            'time_location' => 'Samedi 14h • Maison des Jeunes Menzah',
            'available_spots' => '15 places',
            'color' => 'primary'
        ],
        [
            'title' => 'Atelier Upcycling Textiles ✂️',
            'date' => '16',
            'month' => 'DÉC',
            'time_location' => 'Dimanche 10h • Fab Lab ENSI',
            'available_spots' => '8 places',
            'color' => 'secondary'
        ],
        [
            'title' => 'Collecte Déchets Électroniques 🔌',
            'date' => '18',
            'month' => 'DÉC',
            'time_location' => 'Mercredi 9h • Place Pasteur',
            'available_spots' => 'Illimité',
            'color' => 'accent'
        ]
    ];

    $testimonials = [
        [
            'name' => 'Ahmed Ben Salem',
            'location' => 'Tunis • Créateur de projets',
            'content' => 'Grâce à Waste2Product, j\'ai transformé 20 palettes en mobilier design ! Ma maison a un style unique et j\'ai économisé plus de 800 DT.',
            'rating' => 5
        ],
        [
            'name' => 'Sarah Mansouri',
            'location' => 'Sfax • Collectionneuse',
            'content' => 'Je collecte les bouteilles plastique dans mon quartier. La plateforme m\'aide à les valoriser et sensibiliser ma communauté !',
            'rating' => 5
        ]
    ];
@endphp

<!-- Main Content -->
<main class="pt-16">
    <!-- Hero Section -->
    <section id="home" class="gradient-hero text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Transformons les déchets
                    <span class="block text-secondary">en ressources 🌱</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto opacity-90">
                    Rejoignez la communauté tunisienne de l'économie circulaire et donnez une seconde vie à vos déchets
                </p>
                <!-- Boutons principaux de la vitrine -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                    <a href="/login" class="btn-primary text-lg px-8 py-4 font-medium">🔐 Se connecter</a>
                    <a href="/register" class="btn-secondary text-lg px-8 py-4 font-medium">✨ S'inscrire gratuitement</a>
                </div>

                <!-- Message d'information -->
              
            </div>
            
            </div>
            
            <!-- Impact Statistics -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
                <div class="text-center">
                    <div class="counter-animation">
                        <div class="text-3xl md:text-4xl font-bold mb-2">{{ number_format($stats['co2_saved']) }}</div>
                        <div class="text-sm opacity-80">Kg CO₂ économisés</div>
                    </div>
                </div>
                <div class="text-center">
                    <div class="counter-animation">
                        <div class="text-3xl md:text-4xl font-bold mb-2">{{ $stats['projects_count'] }}</div>
                        <div class="text-sm opacity-80">Projets réalisés</div>
                    </div>
                </div>
                <div class="text-center">
                    <div class="counter-animation">
                        <div class="text-3xl md:text-4xl font-bold mb-2">{{ $stats['wastes_count'] }}</div>
                        <div class="text-sm opacity-80">Déchets valorisés</div>
                    </div>
                </div>
                <div class="text-center">
                    <div class="counter-animation">
                        <div class="text-3xl md:text-4xl font-bold mb-2">{{ $stats['users_count'] }}</div>
                        <div class="text-sm opacity-80">Membres actifs</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Projects Section -->
    <section id="projets" class="py-16 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    Projets populaires 🔥
                </h2>
                <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Découvrez les créations les plus appréciées par notre communauté tunisienne
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($popularProjects as $project)
                <!-- Project Card -->
                <div class="card-hover bg-white dark:bg-gray-700 rounded-xl shadow-lg overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-primary to-success flex items-center justify-center">
                        <i class="fas fa-{{ $project['icon'] }} text-white text-4xl"></i>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="bg-{{ $project['difficulty_color'] }} text-white px-3 py-1 rounded-full text-sm">
                                {{ $project['difficulty'] }}
                            </span>
                            <span class="text-gray-500 text-sm">⏱️ {{ $project['duration'] }}h</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            {{ $project['title'] }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">
                            Créé par {{ $project['creator'] }} • {{ $project['city'] }}
                        </p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-star text-yellow-400"></i>
                                <span class="text-sm text-gray-600 dark:text-gray-300">
                                    {{ $project['rating'] }} ({{ $project['reviews_count'] }})
                                </span>
                            </div>
                            <button class="btn-primary text-sm px-4 py-2">Voir détails</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
           
        </div>
    </section>

    <!-- Recent Wastes Section -->
    <section id="dechets" class="py-16 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    Déchets récents ♻️
                </h2>
                <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Trouvez des matériaux près de chez vous pour vos prochains projets
                </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($recentWastes as $waste)
                <!-- Waste Item -->
                <div class="card-hover bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                    <div class="h-32 bg-gradient-to-br from-primary to-neutral flex items-center justify-center">
                        <i class="fas fa-{{ $waste['icon'] }} text-white text-2xl"></i>
                    </div>
                    <div class="p-4">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $waste['title'] }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">📍 {{ $waste['location'] }}</p>
                        <p class="text-xs text-gray-500 mb-3">{{ $waste['time_ago'] }}</p>
                        <button class="w-full btn-primary text-sm py-2">Voir détails</button>
                    </div>
                </div>
                @endforeach
            </div>
            
          
        </div>
    </section>

    <!-- Upcoming Events Section -->
    <section id="evenements" class="py-16 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    Événements à venir 📅
                </h2>
                <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Participez aux ateliers et événements de votre région
                </p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @foreach($upcomingEvents as $event)
                <!-- Event -->
                <div class="card-hover bg-white dark:bg-gray-700 rounded-xl shadow-lg p-6">
                    <div class="flex items-start space-x-4">
                        <div class="bg-{{ $event['color'] }} text-white rounded-lg p-3 flex-shrink-0">
                            <div class="text-center">
                                <div class="text-xs font-medium">{{ $event['month'] }}</div>
                                <div class="text-lg font-bold">{{ $event['date'] }}</div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                {{ $event['title'] }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                                {{ $event['time_location'] }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs bg-success text-white px-2 py-1 rounded-full">
                                    {{ $event['available_spots'] }}
                                </span>
                                <button class="btn-primary text-sm px-4 py-1">S'inscrire</button>
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
    <section class="py-16 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- CTA 1 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4 floating-icon">
                        <i class="fas fa-plus text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                        Déclarer un déchet
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        Partagez vos matériaux inutilisés avec la communauté
                    </p>
                    <a href="/dechets/nouveau" class="btn-primary px-6 py-3">Commencer 🚀</a>
                </div>
                
                <!-- CTA 2 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-secondary rounded-full flex items-center justify-center mx-auto mb-4 floating-icon" style="animation-delay: -1s;">
                        <i class="fas fa-lightbulb text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                        Créer un projet
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        Partagez vos idées créatives de transformation
                    </p>
                    <a href="/projets/nouveau" class="btn-secondary px-6 py-3">Créer 💡</a>
                </div>
                
                <!-- CTA 3 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-success rounded-full flex items-center justify-center mx-auto mb-4 floating-icon" style="animation-delay: -2s;">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                        Rejoindre la communauté
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
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