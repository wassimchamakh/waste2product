@extends('FrontOffice.layout1.app')

@section('title', 'Mes Projets - Waste2Product')

@section('content')
<!-- Hero Section -->
<div class="gradient-hero text-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">
                <i class="fas fa-folder-open mr-3"></i>Mes Projets
            </h1>
            <p class="text-lg opacity-90">Gérez vos projets DIY ici.</p>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-secondary to-accent text-white px-6 py-3 rounded-lg font-medium hover:shadow-lg transition-all">
                <i class="fas fa-plus-circle"></i> Créer un nouveau projet
            </a>
        </div>
    </div>
</div>

<!-- Projects Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if(isset($projects) && $projects->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
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
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full mb-6">
                <i class="fas fa-folder-open text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Aucun projet trouvé</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Vous n'avez pas encore de projets. Commencez à créer votre premier projet !</p>
            <a 
                href="{{ route('projects.create') }}" 
                class="inline-flex items-center gap-2 bg-primary hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors"
            >
                <i class="fas fa-plus-circle"></i> Créer un projet
            </a>
        </div>
    @endif
</div>
@endsection