@extends('FrontOffice.layout1.app')

@section('title', 'Parcourir les Tutoriels - Waste2Product')

@push('styles')
<style>
    .difficulty-beginner { 
        @apply bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200; 
    }
    .difficulty-intermediate { 
        @apply bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200; 
    }
    .difficulty-advanced { 
        @apply bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200; 
    }
    .difficulty-expert { 
        @apply bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200; 
    }
    
    .category-recycling { 
        @apply bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200; 
    }
    .category-composting { 
        @apply bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200; 
    }
    .category-energy { 
        @apply bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200; 
    }
    .category-water { 
        @apply bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200; 
    }
    .category-waste-reduction { 
        @apply bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200; 
    }
    .category-gardening { 
        @apply bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200; 
    }
    .category-diy { 
        @apply bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200; 
    }
    .category-general { 
        @apply bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200; 
    }
    
    .tutorial-card {
        transition: all 0.3s ease;
    }
    .tutorial-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-primary to-secondary py-16 overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
            Parcourir les Tutoriels
        </h1>
        <p class="text-xl text-white/90 max-w-2xl mx-auto">
            Découvrez des guides complets pour vous aider à apprendre des pratiques durables et avoir un impact environnemental positif
        </p>
        <div class="mt-6 text-white/80">
            <span class="inline-flex items-center">
                <i class="fas fa-graduation-cap mr-2"></i>
                {{ $tutorials->total() }} tutoriels disponibles
            </span>
        </div>
    </div>
</section>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Navigation Tabs and Create Button -->
    @auth
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('tutorials.index') }}" 
               class="inline-flex items-center px-6 py-3 {{ !request()->has('filter') ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600' }} rounded-lg hover:shadow-md transition-all font-medium">
                <i class="fas fa-th-large mr-2"></i>
                Tous les Tutoriels
                <span class="ml-2 px-2 py-0.5 text-xs bg-white/20 rounded-full">{{ $tutorials->total() }}</span>
            </a>
        
        <a href="{{ route('tutorials.index', ['filter' => 'my-tutorials']) }}" 
           class="inline-flex items-center px-6 py-3 {{ request('filter') == 'my-tutorials' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600' }} rounded-lg hover:shadow-md transition-all font-medium">
            <i class="fas fa-user-edit mr-2"></i>
            Mes Tutoriels
        </a>
        
        <a href="{{ route('tutorials.index', ['filter' => 'in-progress']) }}" 
           class="inline-flex items-center px-6 py-3 {{ request('filter') == 'in-progress' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600' }} rounded-lg hover:shadow-md transition-all font-medium">
            <i class="fas fa-book-reader mr-2"></i>
            En Cours d'Apprentissage
        </a>
        
        <a href="{{ route('tutorials.index', ['filter' => 'completed']) }}" 
           class="inline-flex items-center px-6 py-3 {{ request('filter') == 'completed' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600' }} rounded-lg hover:shadow-md transition-all font-medium">
            <i class="fas fa-check-circle mr-2"></i>
            Terminés
        </a>
        </div>
        
        <!-- Create Tutorial Button -->
        <a href="{{ route('tutorials.create') }}" 
           class="inline-flex items-center px-6 py-3 bg-secondary text-white rounded-lg hover:bg-teal-600 transition-all font-medium shadow-md hover:shadow-lg">
            <i class="fas fa-plus-circle mr-2"></i>
            Créer un Tutoriel
        </a>
    </div>
    @endauth

    <!-- Search and Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <form method="GET" action="{{ route('tutorials.index') }}" class="space-y-4">
            <!-- Search Bar -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" 
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Rechercher des tutoriels par titre, description ou tags..." 
                       class="block w-full pl-10 pr-3 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>
            
            <!-- Filter Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catégorie</label>
                    <select name="category" class="block w-full px-3 py-2 text-base border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white" onchange="this.form.submit()">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $key => $categoryName)
                            <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                {{ $categoryName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Difficulty Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Niveau</label>
                    <select name="difficulty" class="block w-full px-3 py-2 text-base border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white" onchange="this.form.submit()">
                        <option value="">Tous les niveaux</option>
                        @foreach($difficultyLevels as $key => $level)
                            <option value="{{ $key }}" {{ request('difficulty') == $key ? 'selected' : '' }}>
                                {{ $level }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Duration Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Durée</label>
                    <select name="duration" class="block w-full px-3 py-2 text-base border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white" onchange="this.form.submit()">
                        <option value="">Toute durée</option>
                        @foreach(['short' => 'Moins de 2 heures', 'medium' => '2-5 heures', 'long' => '5+ heures'] as $key => $duration)
                            <option value="{{ $key }}" {{ request('duration') == $key ? 'selected' : '' }}>
                                {{ $duration }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Sort Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trier par</label>
                    <select name="sort" class="block w-full px-3 py-2 text-base border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white" onchange="this.form.submit()">
                        @foreach(['newest' => 'Plus récents', 'popular' => 'Plus populaires', 'rated' => 'Mieux notés', 'shortest' => 'Plus courts'] as $key => $sort)
                            <option value="{{ $key }}" {{ request('sort', 'newest') == $key ? 'selected' : '' }}>
                                {{ $sort }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Filter Actions -->
            <div class="flex justify-between items-center pt-4">
                <a href="{{ route('tutorials.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                    <i class="fas fa-undo mr-1"></i>Effacer tous les filtres
                </a>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-green-600 transition-colors text-sm font-medium">
                    <i class="fas fa-search mr-1"></i>Appliquer les filtres
                </button>
            </div>
        </form>
    </div>

    <!-- Results Summary -->
    <div class="flex justify-between items-center mb-6">
        <div class="text-gray-600 dark:text-gray-400">
            <span>{{ $tutorials->count() }}</span> sur <span>{{ $tutorials->total() }}</span> tutoriels
            @if(request()->hasAny(['search', 'category', 'difficulty', 'duration']))
                <span class="text-primary font-medium ml-2">
                    <i class="fas fa-filter mr-1"></i>Filtrés
                </span>
            @endif
        </div>
        <div class="flex space-x-2">
            <button id="gridView" class="p-2 bg-primary text-white rounded-lg" title="Vue grille">
                <i class="fas fa-th"></i>
            </button>
            <button id="listView" class="p-2 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors" title="Vue liste">
                <i class="fas fa-list"></i>
            </button>
        </div>
    </div>

    @if($tutorials->isEmpty())
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-search text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucun tutoriel trouvé</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                @if(request()->hasAny(['search', 'category', 'difficulty', 'duration']))
                    Essayez d'ajuster vos critères de recherche ou
                    <a href="{{ route('tutorials.index') }}" class="text-primary hover:text-green-600 underline">parcourir tous les tutoriels</a>.
                @else
                    Soyez le premier à créer un tutoriel !
                @endif
            </p>
            @auth
                <a href="{{ route('tutorials.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Créer un tutoriel
                </a>
            @endauth
        </div>
    @else
        <!-- Tutorials Grid -->
        <div id="tutorialsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($tutorials as $tutorial)
                <div class="tutorial-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300">
                    <!-- Thumbnail -->
                    <div class="relative overflow-hidden">
                        <img src="{{ $tutorial->thumbnail_url ?? 'https://picsum.photos/400/250?random=' . $tutorial->id }}" 
                             alt="{{ $tutorial->title }}" 
                             class="w-full h-48 object-cover">
                        
                        <!-- Difficulty Badge -->
                        <div class="absolute top-3 left-3">
                            <span class="px-2 py-1 text-xs font-medium rounded-full difficulty-{{ strtolower($tutorial->difficulty_level) }}">
                                {{ $tutorial->difficulty_level }}
                            </span>
                        </div>
                        
                        <!-- Category Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="px-2 py-1 text-xs font-medium rounded-full category-{{ $tutorial->category_slug }}">
                                {{ $tutorial->category }}
                            </span>
                        </div>

                        <!-- Featured Badge -->
                        @if($tutorial->is_featured)
                            <div class="absolute bottom-3 left-3">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-warning text-white">
                                    <i class="fas fa-star mr-1"></i>En vedette
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6">
                        <!-- Title -->
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">
                            {{ $tutorial->title }}
                        </h3>
                        
                        <!-- Author -->
                        <div class="flex items-center mb-3">
                            <img src="{{ $tutorial->creator->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($tutorial->creator->name ?? 'Inconnu') }}" 
                                 alt="{{ $tutorial->creator->name ?? 'Inconnu' }}" 
                                 class="w-6 h-6 rounded-full mr-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                par {{ $tutorial->creator->name ?? 'Auteur inconnu' }}
                            </span>
                        </div>
                        
                        <!-- Description -->
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">
                            {{ Str::limit($tutorial->description, 100) }}
                        </p>
                        
                        <!-- Stats Row -->
                        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <div class="flex items-center space-x-3">
                                <span class="flex items-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $tutorial->duration }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-eye mr-1"></i>
                                    {{ number_format($tutorial->views_count) }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="flex items-center">
                                    <i class="fas fa-list mr-1"></i>
                                    {{ $tutorial->steps_count }} étapes
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-comments mr-1"></i>
                                    {{ $tutorial->comments_count }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Rating -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="flex text-yellow-400 mr-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star text-sm {{ $i <= floor($tutorial->average_rating) ? '' : 'text-gray-300 dark:text-gray-600' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ number_format($tutorial->average_rating, 1) }} ({{ $tutorial->total_ratings }})
                                </span>
                            </div>
                            
                            <!-- Completion Badge -->
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-check-circle mr-1"></i>
                                {{ number_format($tutorial->completion_count) }} terminé(s)
                            </div>
                        </div>

                        <!-- Tags -->
                        @if($tutorial->tags)
                            <div class="mb-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($tutorial->tags_array, 0, 3) as $tag)
                                        <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs rounded">
                                            {{ trim($tag) }}
                                        </span>
                                    @endforeach
                                    @if(count($tutorial->tags_array) > 3)
                                        <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs rounded">
                                            +{{ count($tutorial->tags_array) - 3 }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <!-- User Progress Indicator (if authenticated) -->
                        @auth
                            @php
                                $userProgress = $tutorial->myProgress();
                            @endphp
                            
                            @if($userProgress)
                                @if($userProgress->is_completed)
                                    <!-- Completed Badge -->
                                    <div class="mb-3 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center text-green-700 dark:text-green-300">
                                                <i class="fas fa-check-circle text-lg mr-2"></i>
                                                <span class="text-sm font-semibold">Terminé</span>
                                            </div>
                                            <span class="text-xs text-green-600 dark:text-green-400">
                                                {{ $userProgress->completed_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <!-- In Progress -->
                                    <div class="mb-3 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-semibold text-blue-700 dark:text-blue-300">En cours</span>
                                            <span class="text-sm font-bold text-blue-700 dark:text-blue-300">{{ $userProgress->progress_percentage }}%</span>
                                        </div>
                                        <div class="w-full bg-blue-100 dark:bg-blue-900/40 rounded-full h-2">
                                            <div class="bg-blue-500 h-full rounded-full transition-all" 
                                                 style="width: {{ $userProgress->progress_percentage }}%"></div>
                                        </div>
                                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                            Étape {{ $userProgress->current_step }} • {{ $userProgress->total_steps_completed }}/{{ $tutorial->steps_count }} terminées
                                        </p>
                                    </div>
                                @endif
                            @endif
                        @endauth
                        
                        <!-- CTA Button -->
                        @php
                            $buttonText = 'Commencer l\'apprentissage';
                            $buttonIcon = 'fa-play';
                            
                            if (Auth::check() && $userProgress) {
                                if ($userProgress->is_completed) {
                                    $buttonText = 'Revoir';
                                    $buttonIcon = 'fa-redo';
                                } else {
                                    $buttonText = 'Continuer';
                                    $buttonIcon = 'fa-play-circle';
                                }
                            }
                        @endphp
                        
                        <!-- Action Buttons -->
                        @if(Auth::check() && $tutorial->created_by == Auth::id())
                            <!-- Creator Actions: Edit, Delete, View -->
                            <div class="flex gap-2">
                                <a href="{{ route('tutorials.edit', $tutorial->slug) }}" 
                                   class="flex-1 bg-blue-500 text-white px-4 py-3 rounded-lg hover:bg-blue-600 transition-colors font-medium text-center">
                                    <i class="fas fa-edit mr-1"></i>Modifier
                                </a>
                                <button onclick="confirmDelete('{{ $tutorial->slug }}', '{{ $tutorial->title }}')" 
                                        class="flex-1 bg-red-500 text-white px-4 py-3 rounded-lg hover:bg-red-600 transition-colors font-medium text-center">
                                    <i class="fas fa-trash mr-1"></i>Supprimer
                                </button>
                            </div>
                            <a href="{{ route('tutorials.show', $tutorial->slug) }}" 
                               class="w-full bg-gray-500 text-white px-4 py-3 rounded-lg hover:bg-gray-600 transition-colors font-medium text-center block mt-2">
                                <i class="fas fa-eye mr-2"></i>Voir
                            </a>
                        @else
                            <!-- Regular View Button -->
                            <a href="{{ route('tutorials.show', $tutorial->slug) }}" 
                               class="w-full bg-primary text-white px-4 py-3 rounded-lg hover:bg-green-600 transition-colors font-medium text-center block">
                                <i class="fas {{ $buttonIcon }} mr-2"></i>{{ $buttonText }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $tutorials->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // View toggle functionality
    document.getElementById('gridView').addEventListener('click', function() {
        this.classList.add('bg-primary', 'text-white');
        this.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'text-gray-600', 'dark:text-gray-400');
        document.getElementById('listView').classList.remove('bg-primary', 'text-white');
        document.getElementById('listView').classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-600', 'dark:text-gray-400');
        document.getElementById('tutorialsGrid').className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8';
        
        localStorage.setItem('tutorials-view', 'grid');
    });

    document.getElementById('listView').addEventListener('click', function() {
        this.classList.add('bg-primary', 'text-white');
        this.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'text-gray-600', 'dark:text-gray-400');
        document.getElementById('gridView').classList.remove('bg-primary', 'text-white');
        document.getElementById('gridView').classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-600', 'dark:text-gray-400');
        document.getElementById('tutorialsGrid').className = 'grid grid-cols-1 gap-6 mb-8';
        
        localStorage.setItem('tutorials-view', 'list');
    });

    // Live search functionality
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function() {
            this.form.submit();
        }, 500));
    }

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

    // Initialize animations on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Restore view preference
        const savedView = localStorage.getItem('tutorials-view');
        if (savedView === 'list') {
            document.getElementById('listView').click();
        }
        
        // Animate tutorial cards on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('.tutorial-card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
            observer.observe(card);
        });
    });
    
    // Delete confirmation function
    function confirmDelete(slug, title) {
        if (confirm(`Êtes-vous sûr de vouloir supprimer le tutoriel "${title}" ?\n\nCette action est irréversible.`)) {
            // Create a form to submit the delete request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/tutorials/${slug}`;
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            // Add DELETE method
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush