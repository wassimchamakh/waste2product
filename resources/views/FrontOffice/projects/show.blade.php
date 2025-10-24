@extends('FrontOffice.layout1.app')

@section('title', $project->title . ' - Waste2Product')

@section('content')
<<<<<<< HEAD
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
=======

<!-- Section principale avec background coloré -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
    <div class="rounded-3xl bg-gradient-to-br from-green-50 to-green-100 shadow-xl p-8 mb-12 border border-green-200">
        <div class="flex flex-col md:flex-row gap-8 items-center md:items-start">
            <!-- Project Image -->
            <div class="w-full md:w-1/3 flex justify-center">
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden w-72 h-72 flex items-center justify-center transform hover:scale-105 transition-transform duration-300">
                    @if($project->photo)
                        <img 
                            src="{{ asset('uploads/projects/' . $project->photo) }}" 
                            alt="{{ $project->title }}"
                            class="object-cover w-full h-full"
                        >
                    @else
                        <img 
                            src="{{ asset('uploads/projects/default.png') }}" 
                            alt="Image par défaut"
                            class="object-cover w-full h-full"
                        >
                    @endif
                </div>
            </div>
            <!-- Project Info -->
            <div class="w-full md:w-2/3">
                <!-- Breadcrumb -->
                <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
                    <a href="{{ route('projects.my') }}" class="hover:text-green-600 transition-colors font-medium">
                        <i class="fas fa-tools mr-2"></i>Mes Projets
                    </a>
                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    <span class="text-green-600 font-semibold">{{ $project->title }}</span>
                </nav>
                <!-- Project Header -->
                <div class="mb-6">
                    <div class="flex flex-wrap items-center gap-3 mb-6">
                        <!-- Category -->
                        <span class="px-4 py-2 rounded-full text-sm font-bold bg-white text-green-700 border-2 border-green-200 shadow-sm">
                            <i class="fas fa-tag mr-2"></i>{{ $project->category->name ?? 'Général' }}
                        </span>
                        <!-- Difficulty -->
                        <span class="px-4 py-2 rounded-full text-sm font-bold text-white shadow-md
                            @if($project->difficulty_level === 'facile') bg-gradient-to-r from-green-500 to-green-600
                            @elseif($project->difficulty_level === 'intermédiaire') bg-gradient-to-r from-orange-500 to-orange-600
                            @elseif($project->difficulty_level === 'difficile') bg-gradient-to-r from-red-500 to-red-600
                            @else bg-gradient-to-r from-gray-400 to-gray-500
                            @endif">
                            <i class="fas fa-signal mr-2"></i>
                            @if($project->difficulty_level === 'facile') Facile
                            @elseif($project->difficulty_level === 'intermédiaire') Intermédiaire
                            @elseif($project->difficulty_level === 'difficile') Difficile
                            @else Non défini
                            @endif
                        </span>
                        <!-- Statut -->
                        <span class="px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md">
                            <i class="fas fa-circle mr-2"></i>{{ ucfirst($project->status) }}
                        </span>
                        @if($project->status === 'featured')
                            <span class="px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-yellow-400 to-yellow-500 text-white shadow-md">
                                <i class="fas fa-star mr-2"></i>Projet vedette
                            </span>
                        @endif
                    </div>
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4 leading-tight">
                        {{ $project->title }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-6 text-gray-700">
                        <div class="flex items-center gap-3 bg-white rounded-xl px-4 py-2 shadow-sm">
                            <i class="fas fa-user text-green-500"></i>
                            <span class="font-medium">Par {{ $project->user->name ?? 'Anonyme' }}</span>
                        </div>
                        <div class="flex items-center gap-3 bg-white rounded-xl px-4 py-2 shadow-sm">
                            <i class="fas fa-clock text-orange-500"></i>
                            <span class="font-medium">{{ $project->estimated_time }}</span>
                        </div>
                        <div class="flex items-center gap-3 bg-white rounded-xl px-4 py-2 shadow-sm">
                            <i class="fas fa-leaf text-green-500"></i>
                            <span class="font-medium">Impact: {{ $project->impact_score }}/10</span>
                        </div>
                        <div class="flex items-center gap-3 bg-white rounded-xl px-4 py-2 shadow-sm">
                            <i class="fas fa-list-ol text-blue-500"></i>
                            <span class="font-medium">{{ $project->steps->count() }} étapes</span>
>>>>>>> tutoral-branch
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
<<<<<<< HEAD
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
=======
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-12">
        <!-- Main Content -->
        <div class="lg:col-span-2 flex flex-col space-y-8 md:space-y-12">
            
            <!-- Description -->
            <section class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-info-circle text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-extrabold text-gray-900">Description du projet</h2>
                        <p class="text-gray-600">Découvrez les détails de ce projet écologique</p>
                    </div>
                </div>
                <div class="prose prose-lg max-w-none">
                    <p class="text-gray-700 leading-relaxed text-lg whitespace-pre-line bg-gray-50 rounded-xl p-6 border border-gray-200">
>>>>>>> tutoral-branch
                        {{ $project->description }}
                    </p>
                </div>
            </section>
            
            <!-- Project Steps -->
<<<<<<< HEAD
            <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
                    <i class="fas fa-list-ol text-primary"></i>
                    Étapes de réalisation
                    <span class="text-lg font-normal text-gray-500">({{ $project->steps->count() }} étapes)</span>
                </h2>
=======
            <section class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-list-ol text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-extrabold text-gray-900">Étapes de réalisation</h2>
                        <p class="text-gray-600">{{ $project->steps->count() }} étapes pour réussir votre projet</p>
                    </div>
                </div>
>>>>>>> tutoral-branch
                
                @if($project->steps->count() > 0)
                    <div class="space-y-8">
                        @foreach($project->steps->sortBy('step_number') as $step)
<<<<<<< HEAD
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
=======
                            <div class="relative bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 border-2 border-green-100 shadow-lg hover:shadow-xl transition-shadow duration-300">
                                <div class="flex items-start gap-6">
                                    <div class="flex-shrink-0">
                                        <span class="w-14 h-14 flex items-center justify-center rounded-full bg-gradient-to-br from-green-500 to-green-600 text-white font-extrabold text-xl shadow-lg border-4 border-white">
                                            {{ $step->step_number }}
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="mb-4">
                                            <h3 class="text-2xl font-extrabold text-gray-900 mb-3 flex items-center gap-3">
                                                <i class="fas fa-arrow-right text-green-500"></i>
                                                {{ $step->title }}
                                            </h3>
                                            @if($step->duration)
                                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-orange-50 text-orange-700 text-sm font-semibold border border-orange-200 shadow-sm">
                                                    <i class="fas fa-clock text-orange-500"></i> Durée estimée: {{ $step->duration }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mb-6">
                                            <p class="text-gray-700 leading-relaxed text-lg bg-white rounded-xl p-4 border border-gray-200">
                                                {{ $step->description }}
                                            </p>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="bg-white rounded-xl p-5 border-2 border-green-200 shadow-sm">
                                                <h4 class="font-extrabold text-green-700 mb-4 flex items-center gap-3">
                                                    <i class="fas fa-cube text-green-500 text-lg"></i>
                                                    Matériaux nécessaires
                                                </h4>
                                                <div class="flex flex-wrap gap-2">
                                                    @if($step->materials_needed)
                                                        @foreach(explode("\n", $step->materials_needed) as $mat)
                                                            @if(trim($mat))
                                                                <span class="inline-flex items-center px-3 py-2 rounded-lg bg-green-50 text-green-700 text-sm font-medium border border-green-200 shadow-sm">
                                                                    <i class="fas fa-cube mr-2 text-green-500"></i>{{ trim($mat) }}
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <span class="text-gray-400 italic">Aucun matériau spécifié</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="bg-white rounded-xl p-5 border-2 border-blue-200 shadow-sm">
                                                <h4 class="font-extrabold text-blue-700 mb-4 flex items-center gap-3">
                                                    <i class="fas fa-wrench text-blue-500 text-lg"></i>
                                                    Outils requis
                                                </h4>
                                                <div class="flex flex-wrap gap-2">
                                                    @if($step->tools_required)
                                                        @foreach(explode("\n", $step->tools_required) as $tool)
                                                            @if(trim($tool))
                                                                <span class="inline-flex items-center px-3 py-2 rounded-lg bg-blue-50 text-blue-700 text-sm font-medium border border-blue-200 shadow-sm">
                                                                    <i class="fas fa-wrench mr-2 text-blue-500"></i>{{ trim($tool) }}
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <span class="text-gray-400 italic">Aucun outil spécifié</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <div class="absolute left-7 top-14 w-1 h-8 bg-gradient-to-b from-green-300 to-green-400"></div>
>>>>>>> tutoral-branch
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
<<<<<<< HEAD
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
=======
                    <div class="text-center py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300">
                        <i class="fas fa-list-ol text-5xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 text-lg font-medium">Aucune étape définie pour ce projet</p>
                    </div>
                @endif
            </section>
        </div>
        
        <!-- Sidebar -->
        <div class="flex flex-col space-y-8">
            
            <!-- Créateur du projet -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-gray-900">Créateur du projet</h3>
                </div>
                
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-700 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        {{ strtoupper(substr($project->user->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="font-extrabold text-gray-900 text-lg">
                            {{ $project->user->name ?? 'Anonyme' }}
                        </h4>
                        <p class="text-gray-600 text-sm">
>>>>>>> tutoral-branch
                            Membre depuis {{ $project->user->created_at->format('Y') ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                
                <!-- Author Stats -->
<<<<<<< HEAD
                <div class="grid grid-cols-2 gap-4 pt-4 border-t dark:border-gray-700">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary">{{ $project->user->projects_count ?? 0 }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Projets</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-secondary">{{ $project->user->followers_count ?? 0 }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Abonnés</div>
=======
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                    <div class="text-center bg-green-50 rounded-xl p-3">
                        <div class="text-2xl font-extrabold text-green-600">{{ $creatorProjectsCount ?? 0 }}</div>
                        <div class="text-xs text-gray-600 font-medium">Projets</div>
                    </div>
                    <div class="text-center bg-blue-50 rounded-xl p-3">
                        <div class="text-2xl font-extrabold text-blue-600"><!--{{ $project->user->followers_count ?? 0 }}--></div>
                        <div class="text-xs text-gray-600 font-medium"><!--Abonnés--></div>
>>>>>>> tutoral-branch
                    </div>
                </div>
            </div>
            
<<<<<<< HEAD
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
=======
            <!-- Statistiques -->
            <div class="bg-gradient-to-br from-green-500 to-green-700 text-white rounded-2xl p-6 shadow-xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3 class="text-xl font-extrabold">Statistiques</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between bg-white bg-opacity-10 rounded-xl p-3">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-eye"></i>
                            Vues
                        </span>
                        <span class="font-extrabold text-xl">{{ $project->views_count ?? 0 }}</span>
                    </div>
                   <!--  <div class="flex items-center justify-between bg-white bg-opacity-10 rounded-xl p-3">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-heart"></i>
                            Favoris
                        </span>
                        <span class="font-extrabold text-xl">{{ $project->favorites_count ?? 0 }}</span>
                    </div>-->
                    <div class="flex items-center justify-between bg-white bg-opacity-10 rounded-xl p-3">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            Réalisations
                        </span>
                        <span class="font-extrabold text-xl">
                            @auth
                                {{ \App\Models\Project::where('user_id', auth()->id())->count() }}
                            @else
                                0
                            @endauth
                        </span>
                    </div>
                    <div class="flex items-center justify-between bg-white bg-opacity-10 rounded-xl p-3">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-star"></i>
                            Note moyenne
                        </span>
                        <span class="font-extrabold text-xl">{{ number_format($averageRating ?? 0, 1) }}/5</span>
                    </div>
                </div>
            </div>

            <!-- Liste complète des matériaux -->
            @if($project->steps->whereNotNull('materials_needed')->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-shopping-basket text-white"></i>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900">Liste des matériaux</h3>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($project->steps->whereNotNull('materials_needed') as $step)
                            <div class="bg-green-50 border-2 border-green-200 rounded-xl p-4">
                                <div class="font-extrabold text-green-700 mb-3 flex items-center gap-2">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-500 text-white font-bold text-sm shadow">
                                        {{ $step->step_number }}
                                    </span>
                                    Étape {{ $step->step_number }}
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(explode("\n", $step->materials_needed) as $mat)
                                        @if(trim($mat))
                                            <span class="inline-flex items-center px-3 py-2 rounded-lg bg-white text-green-700 text-sm font-medium border border-green-300 shadow-sm">
                                                <i class="fas fa-cube mr-2 text-green-500"></i>{{ trim($mat) }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Actions -->
            <!-- Notation par étoiles -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-green-200 mb-8 flex flex-col items-center">
                <h3 class="text-xl font-extrabold text-green-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-star text-yellow-400"></i> Noter ce projet
                </h3>
                @auth
                <form id="rating-form" method="POST" action="{{ route('projects.rate', $project->id) }}" class="flex flex-col items-center">
                    @csrf
                    <div class="flex items-center gap-2 mb-2" id="star-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star text-3xl cursor-pointer {{ ($userRating ?? 0) >= $i ? 'text-yellow-400' : 'text-gray-300' }}" data-value="{{ $i }}">
                                <i class="fas fa-star"></i>
                            </span>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-value" value="{{ $userRating ?? 0 }}">
                    <button type="submit" class="mt-2 bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-bold transition">Valider ma note</button>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const stars = document.querySelectorAll('#star-rating .star');
                            const ratingInput = document.getElementById('rating-value');
                            let selected = parseInt(ratingInput.value) || 0;

                            function updateStars(rating) {
                                stars.forEach((star, idx) => {
                                    if (idx < rating) {
                                        star.classList.remove('text-gray-300');
                                        star.classList.add('text-yellow-400');
                                    } else {
                                        star.classList.remove('text-yellow-400');
                                        star.classList.add('text-gray-300');
                                    }
                                });
                            }

                            stars.forEach((star, idx) => {
                                star.addEventListener('mouseenter', function() {
                                    updateStars(idx + 1);
                                });
                                star.addEventListener('mouseleave', function() {
                                    updateStars(selected);
                                });
                                star.addEventListener('click', function() {
                                    selected = idx + 1;
                                    ratingInput.value = selected;
                                    updateStars(selected);
                                });
                            });
                            updateStars(selected);
                        });
                    </script>
                </form>
                @else
                <div class="text-gray-500">Connectez-vous pour noter ce projet.</div>
                @endauth
                <div class="mt-3 text-sm text-gray-600">
                    Moyenne&nbsp;: <span class="font-bold text-yellow-600">{{ $averageRating ?? '-' }}</span> / 5
                    ({{ $ratingsCount ?? 0 }} votes)
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-tools text-white"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-gray-900">Actions</h3>
                </div>
                
                <div class="space-y-3">
                    {{-- Bouton Publier le projet : toujours visible en haut de la section Actions pour le propriétaire --}}
                    @if(auth()->check() && $project->user_id === auth()->id())
                        @if($project->status === 'draft')
                            <form method="POST" action="{{ route('projects.publish', $project->id) }}" class="w-full mb-3">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-3 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300">
                                    <i class="fas fa-globe"></i>
                                    Publier le projet
                                </button>
                            </form>
                        @else
                            <button class="w-full flex items-center justify-center gap-3 bg-gray-400 text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg cursor-not-allowed mb-3" disabled>
                                <i class="fas fa-globe"></i>
                                Projet déjà publié
                            </button>
                        @endif
                    @endif
                    <!-- Like Button -->
                    @auth
                    <button 
                        onclick="toggleLike({{ $project->id }})"
                        class="w-full flex items-center justify-center gap-3 bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300"
                        id="like-btn-main"
                    >
                        <i class="fas fa-thumbs-up"></i>
                        <span id="like-text">
                            {{ $project->likes->where('user_id', auth()->id())->count() ? 'Je n\'aime plus' : 'J\'aime' }}
                        </span>
                        <span class="ml-2 font-extrabold bg-white bg-opacity-20 rounded-full px-2 py-1 text-sm" id="like-count">{{ $project->likes->count() }}</span>
                    </button>
                    @endauth
                    @guest
                    <div class="w-full flex items-center justify-center gap-3 bg-gray-300 text-gray-700 px-6 py-4 rounded-xl font-bold text-lg">
                        <i class="fas fa-thumbs-up"></i>
                        <span>{{ $project->likes->count() }} J'aime</span>
                    </div>
                    @endguest

                    <!-- Favorite Button -->
                   <!-- @if(!(auth()->check() && $project->user_id === auth()->id()))
                        <button 
                            onclick="toggleFavorite({{ $project->id }})"
                            class="w-full flex items-center justify-center gap-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300"
                            id="favorite-btn-main"
                        >
                            <i class="fas fa-heart"></i>
                            <span id="favorite-text">{{ $project->is_favorited ?? false ? 'Retirer des favoris' : 'Ajouter aux favoris' }}</span>
                        </button>-->

                        <!-- Share Button -->
                        <button 
                            onclick="shareProject()"
                            class="w-full flex items-center justify-center gap-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300"
                        >
                            <i class="fas fa-share"></i>
                            Partager le projet
                        </button>
                    @endif

                    @if(auth()->check() && $project->user_id === auth()->id())
                        @if($project->status === 'draft')
                            <form method="POST" action="{{ route('projects.publish', $project->id) }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-3 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300">
                                    <i class="fas fa-globe"></i>
                                    Publier le projet
                                </button>
                            </form>
                        @else
                            <button class="w-full flex items-center justify-center gap-3 bg-gray-400 text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg cursor-not-allowed" disabled>
                                <i class="fas fa-globe"></i>
                                Projet déjà publié
                            </button>
                        @endif
                    @endif

                    <!-- Mark as Completed -->
                    <button 
                        onclick="markAsCompleted({{ $project->id }})"
                        class="w-full flex items-center justify-center gap-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300"
>>>>>>> tutoral-branch
                    >
                        <i class="fas fa-check-circle"></i>
                        J'ai réalisé ce projet
                    </button>
<<<<<<< HEAD
                    
                    @if(auth()->check() && $project->user_id === auth()->id())
                        <div class="pt-4 border-t dark:border-gray-700">
                            <a 
                                href="{{ route('projects.edit', $project->id) }}" 
                                class="w-full flex items-center justify-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-colors mb-3"
=======

                    @if(auth()->check() && $project->user_id === auth()->id())
                        <div class="pt-4 border-t border-gray-200 space-y-3">
                            <a 
                                href="{{ route('projects.edit', $project->id) }}" 
                                class="w-full flex items-center justify-center gap-3 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300"
>>>>>>> tutoral-branch
                            >
                                <i class="fas fa-edit"></i>
                                Modifier le projet
                            </a>
                            
                            <button 
                                onclick="showDeleteModal()"
<<<<<<< HEAD
                                class="w-full flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-medium transition-colors"
=======
                                class="w-full flex items-center justify-center gap-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300"
>>>>>>> tutoral-branch
                            >
                                <i class="fas fa-trash"></i>
                                Supprimer le projet
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            
<<<<<<< HEAD
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
=======
        </div>
    </div>

    <!-- Section Commentaires - Pleine largeur -->
    <div class="mt-12">
        <section class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-comments text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900">Commentaires</h2>
                    <p class="text-gray-600">Rejoignez la discussion sur ce projet</p>
                </div>
            </div>

            <!-- Formulaire d'ajout de commentaire -->
            @auth
            <div class="mb-8 bg-gray-50 rounded-2xl p-6 border border-gray-200">
                <form method="POST" action="{{ route('comments.store', $project->id) }}" class="space-y-4">
                    @csrf
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center font-bold text-white text-lg shadow-lg flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <textarea name="content" rows="4" 
                                class="w-full rounded-xl border-2 border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-300 resize-none p-4 text-lg"
                                placeholder="Partagez vos pensées sur ce projet..."></textarea>
                            <div class="flex justify-between items-center mt-3">
                                <div class="flex items-center gap-2 text-gray-500 text-sm">
                                    <i class="fas fa-info-circle"></i>
                                    Votre commentaire sera public
                                </div>
                                <button type="submit" 
                                    class="bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300">
                                    <i class="fas fa-paper-plane mr-2"></i>Publier
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @else
            <div class="mb-8 bg-blue-50 rounded-2xl p-6 border-2 border-blue-200 text-center">
                <p class="text-blue-700 text-lg font-medium">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    <a href="{{ route('login') }}" class="underline hover:text-blue-800">Connectez-vous</a> pour laisser un commentaire
                </p>
            </div>
            @endauth

            <!-- Liste des commentaires -->
            <div class="space-y-6">
                @forelse($project->comments as $comment)
                    <div class="bg-white rounded-2xl p-6 border-2 border-gray-100 hover:border-purple-200 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <!-- Avatar utilisateur -->
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center font-bold text-white text-lg shadow-lg flex-shrink-0">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                            
                            <!-- Contenu du commentaire -->
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <h4 class="font-extrabold text-gray-900 text-lg">{{ $comment->user->name }}</h4>
                                    <span class="text-sm text-gray-500 bg-gray-100 rounded-full px-3 py-1">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center justify-between mb-4">
                                    <p class="text-gray-700 text-lg leading-relaxed">
                                        {{ $comment->filtered_content }}
                                    </p>
                                    @if(auth()->check() && $comment->user_id === auth()->id())
                                        <form method="POST" action="{{ route('comments.destroy', $comment->id) }}" onsubmit="return confirm('Voulez-vous vraiment supprimer ce commentaire ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Supprimer le commentaire" class="ml-2 text-red-500 hover:text-red-700 transition-colors">
                                                <i class="fas fa-trash-alt fa-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Réponses aux commentaires (optionnel) -->
                        @if($comment->replies && $comment->replies->count() > 0)
                            <div class="ml-16 mt-4 space-y-4 border-l-2 border-purple-200 pl-4">
                                @foreach($comment->replies as $reply)
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="font-bold text-gray-900 text-sm">{{ $reply->user->name }}</span>
                                            <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-700 text-sm">{{ $reply->content }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300">
                        <i class="fas fa-comments text-5xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-extrabold text-gray-600 mb-2">Aucun commentaire pour l'instant</h3>
                        <p class="text-gray-500 text-lg">Soyez le premier à partager votre avis sur ce projet !</p>
                    </div>
                @endforelse
            </div>
        </section>
>>>>>>> tutoral-branch
    </div>
</div>

<!-- Similar Projects -->
@if(isset($similarProjects) && $similarProjects->count() > 0)
<<<<<<< HEAD
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
        
=======
<section class="relative z-10 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-12 flex flex-col items-center">
            <div class="w-full flex items-center justify-center mb-6">
                <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center shadow-lg border-4 border-green-300">
                    <i class="fas fa-project-diagram text-green-500 text-4xl"></i>
                </div>
            </div>
            <h2 class="text-4xl font-extrabold text-green-700 mb-2 tracking-tight">Projets similaires</h2>
            <p class="text-gray-600 text-lg font-medium">Découvrez d'autres projets de la même catégorie qui pourraient vous inspirer.</p>
            <div class="w-24 h-1 bg-gradient-to-r from-green-400 to-green-600 rounded-full mt-4 mb-2"></div>
        </div>
>>>>>>> tutoral-branch
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($similarProjects as $similar)
                @include('FrontOffice.projects.partials.project-card', ['project' => $similar])
            @endforeach
        </div>
    </div>
<<<<<<< HEAD
</div>
=======
</section>
>>>>>>> tutoral-branch
@endif

<!-- Delete Modal -->
@if(auth()->check() && $project->user_id === auth()->id())
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
<<<<<<< HEAD
    <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-2xl max-w-md w-full mx-4">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Confirmer la suppression</h3>
            <p class="text-gray-600 dark:text-gray-300">
=======
    <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-md w-full mx-4">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-extrabold text-gray-900 mb-2">Confirmer la suppression</h3>
            <p class="text-gray-600">
>>>>>>> tutoral-branch
                Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.
            </p>
        </div>
        
        <div class="flex gap-4">
<<<<<<< HEAD
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
=======
            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-xl font-bold transition-colors">
                    Supprimer
                </button>
            </form>
            <button onclick="hideDeleteModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-bold transition-colors">
                Annuler
            </button>
>>>>>>> tutoral-branch
        </div>
    </div>
</div>
@endif
<<<<<<< HEAD

@push('scripts')
<script>
// Toggle favorite
function toggleFavorite(projectId) {
    // Implementation for favorite toggle
    fetch(`/projects/${projectId}/favorite`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
=======
@endsection

@push('scripts')
<script>
// Toggle Like
function toggleLike(projectId) {
    fetch(`/projects/${projectId}/toggle-like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
>>>>>>> tutoral-branch
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
<<<<<<< HEAD
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
=======
        const likeBtn = document.getElementById('like-btn-main');
        const likeText = document.getElementById('like-text');
        const likeCount = document.getElementById('like-count');
        if (data.liked) {
            likeText.textContent = "Je n'aime plus";
            likeBtn.classList.add('bg-pink-600');
        } else {
            likeText.textContent = "J'aime";
            likeBtn.classList.remove('bg-pink-600');
        }
        likeCount.textContent = data.likes_count;
    })
    .catch(error => console.error('Error:', error));
}

// Toggle favorite
function toggleFavorite(projectId) {
    fetch(`/projects/${projectId}/toggle-favorite`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        const favoriteBtn = document.getElementById('favorite-btn-main');
        const favoriteText = document.getElementById('favorite-text');
        
        if (data.is_favorited) {
            favoriteText.textContent = 'Retirer des favoris';
            favoriteBtn.classList.add('bg-red-600');
        } else {
            favoriteText.textContent = 'Ajouter aux favoris';
            favoriteBtn.classList.remove('bg-red-600');
        }
    })
    .catch(error => console.error('Error:', error));
>>>>>>> tutoral-branch
}

// Share project
function shareProject() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $project->title }}',
<<<<<<< HEAD
            text: '{{ Str::limit($project->description, 100) }}',
=======
            text: 'Découvrez ce projet sur Waste2Product',
>>>>>>> tutoral-branch
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
<<<<<<< HEAD
@endpush
@endsection
=======
@endpush
>>>>>>> tutoral-branch
