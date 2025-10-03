@extends('FrontOffice.layout1.app')

@section('title', $project->title . ' - Waste2Product')

@section('content')
<!-- Hero Section -->
<div class="relative">
    <!-- Background Image -->
    <div class="h-96 overflow-hidden">
        @if($project->photo)
            <img 
                src="{{ asset('uploads/projects/' . $project->photo) }}" 
                alt="{{ $project->title }}"
                class="w-full h-full object-cover"
            >
        @else
            <div class="w-full h-full bg-gradient-to-br from-primary to-green-700"></div>
        @endif
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    </div>
    
    <!-- Content Overlay -->
    <div class="absolute inset-0 flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="max-w-4xl">
                <!-- Breadcrumb -->
                <nav class="flex items-center space-x-2 text-sm text-white opacity-90 mb-6">
                    <a href="{{ route('projects.my') }}" class="hover:text-warning transition-colors">
                        <i class="fas fa-tools mr-1"></i>Mes Projets
                    </a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span>{{ $project->title }}</span>
                </nav>
                
                <!-- Project Header -->
                <div class="mb-6">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <!-- Category -->
                        <span class="px-4 py-2 rounded-full text-sm font-semibold bg-white bg-opacity-20 text-white backdrop-blur-sm">
                            {{ $project->category->name ?? 'Général' }}
                        </span>
                        
                        <!-- Difficulty -->
                        <span class="px-4 py-2 rounded-full text-sm font-semibold backdrop-blur-sm
                            @if($project->difficulty_level === 'easy') bg-green-500 text-white
                            @elseif($project->difficulty_level === 'medium') bg-orange-500 text-white
                            @else bg-red-500 text-white
                            @endif">
                            @if($project->difficulty_level === 'easy') Facile
                            @elseif($project->difficulty_level === 'medium') Moyen
                            @else Difficile
                            @endif
                        </span>
                        
                        @if($project->status === 'featured')
                            <span class="px-4 py-2 rounded-full text-sm font-bold bg-warning text-white backdrop-blur-sm">
                                <i class="fas fa-star mr-1"></i>Projet vedette
                            </span>
                        @endif
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                        {{ $project->title }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-6 text-white">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-user"></i>
                            <span>Par {{ $project->user->name ?? 'Anonyme' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-clock"></i>
                            <span>{{ $project->estimated_time }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-leaf"></i>
                            <span>Impact environnemental: {{ $project->impact_score }}/10</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-list-ol"></i>
                            <span>{{ $project->steps->count() }} étapes</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-12">
            
            <!-- Description -->
            <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                    <i class="fas fa-info-circle text-primary"></i>
                    Description du projet
                </h2>
                <div class="prose prose-gray dark:prose-invert max-w-none">
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                        {{ $project->description }}
                    </p>
                </div>
            </section>
            
            <!-- Project Steps -->
            <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
                    <i class="fas fa-list-ol text-primary"></i>
                    Étapes de réalisation
                    <span class="text-lg font-normal text-gray-500">({{ $project->steps->count() }} étapes)</span>
                </h2>
                
                @if($project->steps->count() > 0)
                    <div class="space-y-8">
                        @foreach($project->steps->sortBy('step_number') as $step)
                            <div class="relative">
                                <!-- Step Number -->
                                <div class="flex items-start gap-6">
                                    <div class="flex-shrink-0 w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-bold text-lg">
                                        {{ $step->step_number }}
                                    </div>
                                    
                                    <div class="flex-1">
                                        <!-- Step Header -->
                                        <div class="mb-4">
                                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                                {{ $step->title }}
                                            </h3>
                                            @if($step->duration)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                                    <i class="fas fa-clock text-secondary"></i>
                                                    Durée estimée: {{ $step->duration }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <!-- Step Description -->
                                        <div class="mb-6">
                                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                                {{ $step->description }}
                                            </p>
                                        </div>
                                        
                                        <!-- Materials and Tools -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            @if($step->materials_needed)
                                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                                        <i class="fas fa-cube text-accent"></i>
                                                        Matériaux nécessaires
                                                    </h4>
                                                    <div class="prose prose-sm prose-gray dark:prose-invert">
                                                        {!! nl2br(e($step->materials_needed)) !!}
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($step->tools_required)
                                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                                        <i class="fas fa-wrench text-secondary"></i>
                                                        Outils requis
                                                    </h4>
                                                    <div class="prose prose-sm prose-gray dark:prose-invert">
                                                        {!! nl2br(e($step->tools_required)) !!}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Connector Line -->
                                @if(!$loop->last)
                                    <div class="absolute left-6 top-12 w-0.5 h-8 bg-gray-300 dark:bg-gray-600"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-list-ol text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 dark:text-gray-400">Aucune étape définie pour ce projet</p>
                    </div>
                @endif
            </section>
            
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-8">
            
            <!-- Author Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-primary"></i>
                    Créateur du projet
                </h3>
                
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-green-700 rounded-full flex items-center justify-center text-white font-bold text-xl">
                        {{ strtoupper(substr($project->user->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white text-lg">
                            {{ $project->user->name ?? 'Anonyme' }}
                        </h4>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Membre depuis {{ $project->user->created_at->format('Y') ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                
                <!-- Author Stats -->
                <div class="grid grid-cols-2 gap-4 pt-4 border-t dark:border-gray-700">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary">{{ $project->user->projects_count ?? 0 }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Projets</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-secondary">{{ $project->user->followers_count ?? 0 }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Abonnés</div>
                    </div>
                </div>
            </div>
            
            <!-- Project Stats -->
            <div class="bg-gradient-to-br from-primary to-green-700 text-white rounded-2xl p-6">
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-bar"></i>
                    Statistiques
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="opacity-90">Vues</span>
                        <span class="font-bold text-xl">{{ $project->views_count ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="opacity-90">Favoris</span>
                        <span class="font-bold text-xl">{{ $project->favorites_count ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="opacity-90">Réalisations</span>
                        <span class="font-bold text-xl">{{ $project->completions_count ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="opacity-90">Note moyenne</span>
                        <span class="font-bold text-xl">{{ number_format($project->average_rating ?? 0, 1) }}/5</span>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-tools text-primary"></i>
                    Actions
                </h3>
                
                <div class="space-y-3">
                    <!-- Favorite Button -->
                    <button 
                        onclick="toggleFavorite({{ $project->id }})"
                        class="w-full flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-medium transition-colors"
                        id="favorite-btn-main"
                    >
                        <i class="fas fa-heart"></i>
                        <span id="favorite-text">{{ $project->is_favorited ?? false ? 'Retirer des favoris' : 'Ajouter aux favoris' }}</span>
                    </button>
                    
                    <!-- Share Button -->
                    <button 
                        onclick="shareProject()"
                        class="w-full flex items-center justify-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-colors"
                    >
                        <i class="fas fa-share"></i>
                        Partager le projet
                    </button>
                    
                    <!-- Mark as Completed -->
                    <button 
                        onclick="markAsCompleted({{ $project->id }})"
                        class="w-full flex items-center justify-center gap-2 bg-success hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium transition-colors"
                    >
                        <i class="fas fa-check-circle"></i>
                        J'ai réalisé ce projet
                    </button>
                    
                    @if(auth()->check() && $project->user_id === auth()->id())
                        <div class="pt-4 border-t dark:border-gray-700">
                            <a 
                                href="{{ route('projects.edit', $project->id) }}" 
                                class="w-full flex items-center justify-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-colors mb-3"
                            >
                                <i class="fas fa-edit"></i>
                                Modifier le projet
                            </a>
                            
                            <button 
                                onclick="showDeleteModal()"
                                class="w-full flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-medium transition-colors"
                            >
                                <i class="fas fa-trash"></i>
                                Supprimer le projet
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Materials Summary -->
            @if($project->steps->whereNotNull('materials_needed')->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-shopping-list text-primary"></i>
                        Liste complète des matériaux
                    </h3>
                    
                    <div class="space-y-3">
                        @foreach($project->steps->whereNotNull('materials_needed') as $step)
                            <div class="text-sm">
                                <div class="font-medium text-gray-900 dark:text-white mb-1">
                                    Étape {{ $step->step_number }}:
                                </div>
                                <div class="text-gray-600 dark:text-gray-400 pl-4">
                                    {!! nl2br(e($step->materials_needed)) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
        </div>
    </div>
</div>

<!-- Similar Projects -->
@if(isset($similarProjects) && $similarProjects->count() > 0)
<div class="bg-gray-50 dark:bg-gray-900 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                Projets similaires
            </h2>
            <p class="text-gray-600 dark:text-gray-400 text-lg">
                Découvrez d'autres projets qui pourraient vous intéresser
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($similarProjects as $similar)
                @include('FrontOffice.projects.partials.project-card', ['project' => $similar])
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Delete Modal -->
@if(auth()->check() && $project->user_id === auth()->id())
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-2xl max-w-md w-full mx-4">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Confirmer la suppression</h3>
            <p class="text-gray-600 dark:text-gray-300">
                Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.
            </p>
        </div>
        
        <div class="flex gap-4">
            <button 
                onclick="hideDeleteModal()" 
                class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-white px-6 py-3 rounded-lg font-medium transition-colors"
            >
                Annuler
            </button>
            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button 
                    type="submit" 
                    class="w-full bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-medium transition-colors"
                >
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
// Toggle favorite
function toggleFavorite(projectId) {
    // Implementation for favorite toggle
    fetch(`/projects/${projectId}/favorite`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        // Update UI
        const btn = document.getElementById('favorite-btn-main');
        const text = document.getElementById('favorite-text');
        if (data.favorited) {
            text.textContent = 'Retirer des favoris';
            btn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
            btn.classList.add('bg-red-500', 'hover:bg-red-600');
        } else {
            text.textContent = 'Ajouter aux favoris';
            btn.classList.remove('bg-red-500', 'hover:bg-red-600');
            btn.classList.add('bg-gray-500', 'hover:bg-gray-600');
        }
    });
}

// Share project
function shareProject() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $project->title }}',
            text: '{{ Str::limit($project->description, 100) }}',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href);
        alert('Lien copié dans le presse-papiers !');
    }
}

// Mark as completed
function markAsCompleted(projectId) {
    if (confirm('Avez-vous vraiment réalisé ce projet ?')) {
        // Implementation for marking as completed
        console.log('Project marked as completed:', projectId);
    }
}

// Modal functions
function showDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function hideDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endpush
@endsection