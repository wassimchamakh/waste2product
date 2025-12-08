@extends('FrontOffice.layout1.app')

@section('title', 'Mes Projets - Waste2Product')

@section('content')
<!-- Hero Section -->
<div class="gradient-hero text-white py-8 sm:py-10 md:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold mb-3 sm:mb-4">
                <i class="fas fa-folder-open mr-2 sm:mr-3"></i>Mes Projets
            </h1>
            <p class="text-sm sm:text-base md:text-lg opacity-90 mb-4 sm:mb-6">Gérez vos projets DIY ici.</p>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-secondary to-accent text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-medium hover:shadow-lg transition-all text-sm sm:text-base">
                <i class="fas fa-plus-circle"></i> <span class="hidden sm:inline">Créer un nouveau projet</span><span class="sm:hidden">Créer</span>
            </a>
        </div>
    </div>
</div>

<!-- Projects Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    @if(isset($projects) && $projects->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8 sm:mb-12">
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
                <i class="fas fa-folder-open text-2xl sm:text-3xl md:text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 dark:text-white mb-2 px-4">Aucun projet trouvé</h3>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mb-4 sm:mb-6 px-4">
                <span class="hidden sm:inline">Vous n'avez pas encore de projets. </span>Commencez <span class="hidden sm:inline">à créer votre premier projet !</span><span class="sm:hidden">maintenant !</span>
            </p>
            <a 
                href="{{ route('projects.create') }}" 
                class="inline-flex items-center gap-2 bg-primary hover:bg-green-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-medium transition-colors text-sm sm:text-base"
            >
                <i class="fas fa-plus-circle"></i> <span class="hidden sm:inline">Créer un projet</span><span class="sm:hidden">Créer</span>
            </a>
        </div>
    @endif
</div>
@endsection