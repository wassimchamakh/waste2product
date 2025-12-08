@extends('FrontOffice.layout1.app')

@section('title', 'Projets DIY - Waste2Product')

@section('content')
<!-- Hero Section -->
<div class="gradient-hero text-white py-8 sm:py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-3 sm:mb-4">
                <i class="fas fa-tools mr-2 sm:mr-3"></i><span class="hidden sm:inline">Projets DIY</span><span class="sm:hidden">Projets</span>
            </h1>
            <p class="text-sm sm:text-base md:text-lg lg:text-xl opacity-90 mb-6 sm:mb-8 px-4">
                Transformez vos déchets en créations utiles<span class="hidden sm:inline"> et esthétiques</span>
            </p>
            <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row justify-center items-center gap-3 sm:gap-4">
                <a href="{{ route('projects.my') }}" class="w-full sm:w-auto inline-block bg-green-500 hover:bg-green-600 text-white font-semibold px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg shadow transition-colors text-sm sm:text-base">
                    <i class="fas fa-folder-open mr-2"></i>Mes Projets
                </a>
            </div>
            <!-- Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6 max-w-4xl mx-auto">
                <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-xl p-6">
                    <div class="text-3xl font-bold">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-sm opacity-90">Projets disponibles</div>
                </div>
                <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-xl p-6">
                    <div class="text-3xl font-bold">{{ $stats['easy'] ?? 0 }}</div>
                    <div class="text-sm opacity-90">Projets faciles</div>
                </div>
                <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-xl p-6">
                    <div class="text-3xl font-bold">{{ $stats['featured'] ?? 0 }}</div>
                    <div class="text-sm opacity-90">Projets vedettes</div>
                </div>
                <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-xl p-6">
                    <div class="text-3xl font-bold">
                        @auth
                            {{ \App\Models\Project::where('user_id', auth()->id())->count() }}
                        @else
                            0
                        @endauth
                    </div>
                    <div class="text-sm opacity-90">Réalisations</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters Section -->
<div class="bg-white dark:bg-gray-800 shadow-lg sticky top-16 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        <form method="GET" action="{{ route('projects.index') }}" class="space-y-3 sm:space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
                <!-- Search -->
                <div class="sm:col-span-2">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Rechercher..." 
                            class="w-full pl-10 sm:pl-12 pr-4 py-2 sm:py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent text-sm sm:text-base"
                        >
                        <i class="fas fa-search absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm sm:text-base"></i>
                    </div>
                </div>
                
                <!-- Category -->
                <div>
                    <select 
                        name="category" 
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent text-sm sm:text-base"
                    >
                        <option value="">Toutes les catégories</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Difficulty -->
                <div>
                    <select 
                        name="difficulty" 
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent text-sm sm:text-base"
                    >
                        <option value="">Toutes difficultés</option>
                        <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>Facile</option>
                        <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>Moyen</option>
                        <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>Difficile</option>
                    </select>
                </div>
                
                <!-- Duration -->
                <div>
                    <select 
                        name="duration" 
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent text-sm sm:text-base"
                    >
                        <option value="">Toutes durées</option>
                        <option value="short" {{ request('duration') == 'short' ? 'selected' : '' }}>< 2h</option>
                        <option value="medium" {{ request('duration') == 'medium' ? 'selected' : '' }}>2-6h</option>
                        <option value="long" {{ request('duration') == 'long' ? 'selected' : '' }}>> 6h</option>
                    </select>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4">
                <div class="flex gap-2 sm:gap-3 w-full sm:w-auto">
                    <button 
                        type="submit" 
                        class="flex-1 sm:flex-initial bg-primary hover:bg-green-700 text-white px-4 sm:px-6 md:px-8 py-2 sm:py-3 rounded-lg font-medium transition-colors flex items-center justify-center gap-2 text-sm sm:text-base"
                    >
                        <i class="fas fa-search"></i>
                        <span class="hidden sm:inline">Filtrer</span>
                    </button>
                    
                    <a 
                        href="{{ route('projects.index') }}" 
                        class="flex-1 sm:flex-initial bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-medium transition-colors flex items-center justify-center text-sm sm:text-base"
                    >
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
                
                <a 
                    href="{{ route('projects.create') }}" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-secondary to-accent text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-medium hover:shadow-lg transition-all text-sm sm:text-base"
                >
                    <i class="fas fa-plus-circle"></i>
                    <span class="hidden sm:inline">Créer un projet</span><span class="sm:hidden">Créer</span>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Featured Projects -->
@if(isset($featuredProjects) && $featuredProjects->count() > 0)
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <div class="mb-6 sm:mb-8">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-2">
            <i class="fas fa-star text-warning mr-2"></i><span class="hidden sm:inline">Projets vedettes</span><span class="sm:hidden">Vedettes</span>
        </h2>
        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Les projets<span class="hidden sm:inline"> les plus populaires de la communauté</span></p>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8 sm:mb-12">
        @foreach($featuredProjects as $project)
            @include('FrontOffice.projects.partials.project-card', ['project' => $project, 'featured' => true])
        @endforeach
    </div>
</div>
@endif

<!-- Projects Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    @if(isset($projects) && $projects->count() > 0)
        <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">
                <span class="hidden sm:inline">Tous les projets</span><span class="sm:hidden">Projets</span>
                <span class="text-sm sm:text-base md:text-lg font-normal text-gray-500">({{ $projects->total() }})</span>
            </h2>
            
            <!-- Sort Options -->
            <div class="flex items-center gap-2 sm:gap-4 w-full sm:w-auto">
                <label class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">Trier :</label>
                <select 
                    name="sort" 
                    onchange="this.form.submit()"
                    class="flex-1 sm:flex-initial px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-xs sm:text-sm"
                >
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récents</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Plus populaires</option>
                    <option value="easiest" {{ request('sort') == 'easiest' ? 'selected' : '' }}>Plus faciles</option>
                    <option value="quickest" {{ request('sort') == 'quickest' ? 'selected' : '' }}>Plus rapides</option>
                </select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
            @foreach($projects as $project)
                @include('FrontOffice.projects.partials.project-card', ['project' => $project])
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8 sm:mt-12">
            {{ $projects->links() }}
        </div>
    @else
        <div class="text-center py-12 sm:py-16">
            <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 bg-gray-100 dark:bg-gray-800 rounded-full mb-4 sm:mb-6">
                <i class="fas fa-search text-2xl sm:text-3xl md:text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 dark:text-white mb-2 px-4">Aucun projet trouvé</h3>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mb-4 sm:mb-6 px-4">
                <span class="hidden sm:inline">Essayez de modifier vos filtres ou </span>
                <a href="{{ route('projects.index') }}" class="text-primary font-medium hover:underline">réinitialisez<span class="hidden sm:inline"> la recherche</span></a>
            </p>
            
            <a 
                href="{{ route('projects.create') }}" 
                class="inline-flex items-center gap-2 bg-primary hover:bg-green-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-medium transition-colors text-sm sm:text-base"
            >
                <i class="fas fa-plus-circle"></i>
                <span class="hidden sm:inline">Créer le premier projet</span><span class="sm:hidden">Créer</span>
            </a>
        </div>
    @endif
</div>

<!-- Call to Action -->
<div class="bg-gradient-to-r from-primary to-green-700 py-12 sm:py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-white mb-3 sm:mb-4">
            <span class="hidden sm:inline">Prêt à créer votre premier projet ?</span><span class="sm:hidden">Créer un projet ?</span>
        </h2>
        <p class="text-sm sm:text-base md:text-lg lg:text-xl text-white opacity-90 mb-6 sm:mb-8 px-4">
            Partagez vos idées<span class="hidden sm:inline"> créatives et inspirez la communauté Waste2Product</span>
        </p>
        <a 
            href="{{ route('projects.create') }}" 
            class="inline-flex items-center gap-2 bg-white text-primary px-4 sm:px-6 md:px-8 py-2.5 sm:py-3 md:py-4 rounded-lg font-bold text-sm sm:text-base md:text-lg hover:shadow-xl transition-all"
        >
            <i class="fas fa-lightbulb"></i>
            Créer mon projet
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-submit form on sort change
document.querySelector('select[name="sort"]').addEventListener('change', function() {
    this.closest('form').submit();
});
</script>
@endpush