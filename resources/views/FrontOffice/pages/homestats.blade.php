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
                    <button class="bg-white text-primary px-6 py-3 rounded-xl font-medium hover:bg-gray-50 transition-colors shadow-md">
                        <i class="fas fa-plus mr-2"></i>Nouveau Projet
                    </button>
                    <button class="bg-white bg-opacity-20 text-white px-6 py-3 rounded-xl font-medium hover:bg-opacity-30 transition-colors border border-white border-opacity-30">
                        <i class="fas fa-recycle mr-2"></i>Déclarer Déchet
                    </button>
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
                <div class="text-3xl font-bold text-gray-900 mb-2">12</div>
                <div class="text-sm text-gray-600 font-medium">Projets Créés</div>
                <div class="text-xs text-success mt-2 font-medium">+2 ce mois</div>
            </div>

            <!-- Stat 2 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-secondary rounded-full flex items-center justify-center mx-auto mb-4 floating-icon shadow-lg" style="animation-delay: -0.5s;">
                    <i class="fas fa-recycle text-white text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-2">28</div>
                <div class="text-sm text-gray-600 font-medium">Déchets Déclarés</div>
                <div class="text-xs text-success mt-2 font-medium">+5 cette semaine</div>
            </div>

            <!-- Stat 3 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-success rounded-full flex items-center justify-center mx-auto mb-4 floating-icon shadow-lg" style="animation-delay: -1s;">
                    <i class="fas fa-leaf text-white text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-2">347</div>
                <div class="text-sm text-gray-600 font-medium">Kg CO₂ Économisés</div>
                <div class="text-xs text-success mt-2 font-medium">Objectif: 500kg</div>
            </div>

            <!-- Stat 4 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-accent rounded-full flex items-center justify-center mx-auto mb-4 floating-icon shadow-lg" style="animation-delay: -1.5s;">
                    <i class="fas fa-star text-white text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-2">4.8</div>
                <div class="text-sm text-gray-600 font-medium">Note Moyenne</div>
                <div class="text-xs text-success mt-2 font-medium">46 évaluations</div>
            </div>
        </div>
    </div>
</section>

<!-- Main Dashboard Content -->
<section class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Mes Projets Récents -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-gray-900">
                                Mes Projets Récents 🔨
                            </h2>
                            <a  class="text-primary hover:text-primary/80 text-sm font-medium">Voir tout</a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Project 1 -->
                            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center">
                                    <i class="fas fa-table text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900">Table Basse Palettes</h3>
                                    <p class="text-sm text-gray-600">En cours • 75% terminé</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8">
                                        <svg class="w-8 h-8 progress-ring" viewBox="0 0 32 32">
                                            <circle cx="16" cy="16" r="14" stroke="#e5e7eb" stroke-width="4" fill="none"/>
                                            <circle cx="16" cy="16" r="14" stroke="#2E7D47" stroke-width="4" fill="none" 
                                                    stroke-dasharray="66 22" class="progress-ring-fill"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-primary">75%</span>
                                </div>
                            </div>

                            <!-- Project 2 -->
                            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                <div class="w-12 h-12 bg-success rounded-lg flex items-center justify-center">
                                    <i class="fas fa-seedling text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900">Jardinière Suspendue</h3>
                                    <p class="text-sm text-gray-600">Terminé • 4.9/5 ⭐</p>
                                </div>
                                <div class="flex items-center">
                                    <span class="bg-success text-white text-xs px-2 py-1 rounded-full">Terminé</span>
                                </div>
                            </div>

                            <!-- Project 3 -->
                            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                <div class="w-12 h-12 bg-secondary rounded-lg flex items-center justify-center">
                                    <i class="fas fa-lightbulb text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900">Luminaire Canettes</h3>
                                    <p class="text-sm text-gray-600">En attente matériaux</p>
                                </div>
                                <div class="flex items-center">
                                    <span class="bg-warning text-gray-900 text-xs px-2 py-1 rounded-full">En attente</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activité Récente -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">
                            Activité Récente 📈
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Activity 1 -->
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-success rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900">
                                        <span class="font-medium">Projet terminé</span> - Jardinière Suspendue
                                    </p>
                                    <p class="text-xs text-gray-500">Il y a 2 heures</p>
                                </div>
                            </div>

                            <!-- Activity 2 -->
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-recycle text-white text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900">
                                        <span class="font-medium">Nouveau déchet déclaré</span> - Palettes Européennes
                                    </p>
                                    <p class="text-xs text-gray-500">Hier à 14h30</p>
                                </div>
                            </div>

                            <!-- Activity 3 -->
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-secondary rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-star text-white text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900">
                                        <span class="font-medium">Nouvelle évaluation reçue</span> - 5 étoiles
                                    </p>
                                    <p class="text-xs text-gray-500">Il y a 3 jours</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                <!-- Objectifs du Mois -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center">
                            Objectifs Décembre <span class="ml-2">🎯</span>
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Goal 1 -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-semibold text-gray-900">Projets créés</span>
                                <span class="text-sm font-bold text-gray-600">2/3</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-primary h-2.5 rounded-full transition-all duration-500" style="width: 67%"></div>
                            </div>
                        </div>

                        <!-- Goal 2 -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-semibold text-gray-900">CO₂ économisé</span>
                                <span class="text-sm font-bold text-gray-600">347/500kg</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-success h-2.5 rounded-full transition-all duration-500" style="width: 69%"></div>
                            </div>
                        </div>

                        <!-- Goal 3 -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-semibold text-gray-900">Événements participés</span>
                                <span class="text-sm font-bold text-gray-600">1/2</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-secondary h-2.5 rounded-full transition-all duration-500" style="width: 50%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Événements Suivis -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">
                            Mes Événements 📅
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Event 1 -->
                        <div class="flex items-start space-x-4">
                            <div class="bg-primary text-white rounded-lg p-2 flex-shrink-0">
                                <div class="text-center">
                                    <div class="text-xs font-medium">DÉC</div>
                                    <div class="text-sm font-bold">15</div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 text-sm">Repair Café Tunis</h3>
                                <p class="text-xs text-gray-600">Samedi 14h • Inscrit</p>
                            </div>
                        </div>

                        <!-- Event 2 -->
                        <div class="flex items-start space-x-4">
                            <div class="bg-secondary text-white rounded-lg p-2 flex-shrink-0">
                                <div class="text-center">
                                    <div class="text-xs font-medium">DÉC</div>
                                    <div class="text-sm font-bold">18</div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 text-sm">Collecte Électroniques</h3>
                                <p class="text-xs text-gray-600">Mercredi 9h • Organisateur</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Badges & Récompenses -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">
                            Mes Badges 🏆
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-3 gap-4">
                            <!-- Badge 1 -->
                            <div class="text-center">
                                <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-trophy text-white"></i>
                                </div>
                                <div class="text-xs font-medium text-gray-900">Éco-Warrior</div>
                            </div>

                            <!-- Badge 2 -->
                            <div class="text-center">
                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-recycle text-white"></i>
                                </div>
                                <div class="text-xs font-medium text-gray-900">Recycleur Pro</div>
                            </div>

                            <!-- Badge 3 -->
                            <div class="text-center">
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <div class="text-xs font-medium text-gray-900">Communauté</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recommandations -->
                <div class="bg-gradient-to-br from-primary to-success text-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold mb-4">
                            Recommandé pour vous 💡
                        </h2>
                        <div class="space-y-3">
                            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                <h3 class="font-medium text-sm mb-1">Étagère Caisses de Vin</h3>
                                <p class="text-xs opacity-90">Basé sur vos matériaux disponibles</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                <h3 class="font-medium text-sm mb-1">Atelier Upcycling</h3>
                                <p class="text-xs opacity-90">Événement près de chez vous</p>
                            </div>
                        </div>
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