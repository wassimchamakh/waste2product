@extends('FrontOffice.layout1.app')

@section('title', 'Projets DIY - Waste2Product')

@section('content')
<!-- Hero Section -->
<div class="gradient-hero text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <i class="fas fa-tools mr-3"></i>Projets DIY
            </h1>
            <p class="text-xl opacity-90 mb-8">
                Transformez vos déchets en créations utiles et esthétiques
            </p>
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
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
                    <div class="text-3xl font-bold">{{ $stats['completed'] ?? 0 }}</div>
                    <div class="text-sm opacity-90">Réalisations</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters Section -->
<div class="bg-white shadow-lg sticky top-16 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <form method="GET" action="{{ route('projects.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Rechercher un projet..." 
                            class="w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent text-base"
                        >
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                
                <!-- Category -->
                <div>
                    <select 
                        name="category" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent text-base"
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
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent text-base"
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
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent text-base"
                    >
                        <option value="">Toutes durées</option>
                        <option value="short" {{ request('duration') == 'short' ? 'selected' : '' }}>< 2h</option>
                        <option value="medium" {{ request('duration') == 'medium' ? 'selected' : '' }}>2-6h</option>
                        <option value="long" {{ request('duration') == 'long' ? 'selected' : '' }}>> 6h</option>
                    </select>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex gap-3 w-full sm:w-auto">
                    <button 
                        type="submit" 
                        class="flex-1 sm:flex-initial bg-primary hover:bg-green-700 text-white px-8 py-3 rounded-lg font-medium transition-colors flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-search"></i>
                        Filtrer
                    </button>
                    
                    <a 
                        href="{{ route('projects.index') }}" 
                        class="flex-1 sm:flex-initial bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium transition-colors"
                    >
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
                
                <a 
                    href="{{ route('projects.create') }}" 
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-secondary to-accent text-white px-6 py-3 rounded-lg font-medium hover:shadow-lg transition-all"
                >
                    <i class="fas fa-plus-circle"></i>
                    Créer un projet
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Featured Projects -->
@if(isset($featuredProjects) && $featuredProjects->count() > 0)
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
            <i class="fas fa-star text-warning mr-2"></i>Projets vedettes
        </h2>
        <p class="text-gray-600">Les projets les plus populaires de la communauté</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        @foreach($featuredProjects as $project)
            @include('FrontOffice.projects.partials.project-card', ['project' => $project, 'featured' => true])
        @endforeach
    </div>
</div>
@endif

<!-- Projects Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if(isset($projects) && $projects->count() > 0)
        <div class="mb-8 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">
                Tous les projets
                <span class="text-lg font-normal text-gray-500">({{ $projects->total() }} résultats)</span>
            </h2>
            
            <!-- Sort Options -->
            <div class="flex items-center gap-4">
                <label class="text-sm text-gray-600">Trier par :</label>
                <select 
                    name="sort" 
                    onchange="this.form.submit()"
                    class="px-3 py-2 rounded-lg border border-gray-300 text-sm"
                >
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récents</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Plus populaires</option>
                    <option value="easiest" {{ request('sort') == 'easiest' ? 'selected' : '' }}>Plus faciles</option>
                    <option value="quickest" {{ request('sort') == 'quickest' ? 'selected' : '' }}>Plus rapides</option>
                </select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($projects as $project)
                @include('FrontOffice.projects.partials.project-card', ['project' => $project])
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-12">
            {{ $projects->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                <i class="fas fa-search text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun projet trouvé</h3>
            <p class="text-gray-600 mb-6">
                Essayez de modifier vos filtres ou 
                <a href="{{ route('projects.index') }}" class="text-primary font-medium hover:underline">réinitialisez la recherche</a>
            </p>
            
            <a 
                href="{{ route('projects.create') }}" 
                class="inline-flex items-center gap-2 bg-primary hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors"
            >
                <i class="fas fa-plus-circle"></i>
                Créer le premier projet
            </a>
        </div>
    @endif
</div>

<!-- Call to Action -->
<div class="bg-gradient-to-r from-primary to-green-700 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">
            Prêt à créer votre premier projet ?
        </h2>
        <p class="text-xl text-white opacity-90 mb-8">
            Partagez vos idées créatives et inspirez la communauté Waste2Product
        </p>
        <a 
            href="{{ route('projects.create') }}" 
            class="inline-flex items-center gap-2 bg-white text-primary px-8 py-4 rounded-lg font-bold text-lg hover:shadow-xl transition-all"
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