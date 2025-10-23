@extends('FrontOffice.layout1.app')

@section('title', 'Mes Déchets - Dechet2Product')

@section('content')
<!-- Hero Section -->
<div class="gradient-hero text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <i class="fas fa-box-open mr-3"></i>Mes Déchets
            </h1>
            <p class="text-xl opacity-90 mb-8">
                Gérez vos déchets déclarés et suivez leurs statuts
            </p>
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto">
                <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-xl p-6">
                    <div class="text-3xl font-bold">{{ $stats['total'] }}</div>
                    <div class="text-sm opacity-90">Total déchets</div>
                </div>
                <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-xl p-6">
                    <div class="text-3xl font-bold">{{ $stats['available'] }}</div>
                    <div class="text-sm opacity-90">Disponibles</div>
                </div>
                <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-xl p-6">
                    <div class="text-3xl font-bold">{{ $stats['reserved'] }}</div>
                    <div class="text-sm opacity-90">Réservés</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions Section -->
<div class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a 
                    href="{{ route('dechets.index') }}" 
                    class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium transition-colors"
                >
                    <i class="fas fa-arrow-left"></i>
                    Tous les déchets
                </a>
            </div>
            
            <a 
                href="{{ route('dechets.create') }}" 
                class="inline-flex items-center gap-2 bg-gradient-to-r from-secondary to-accent text-white px-6 py-3 rounded-lg font-medium hover:shadow-lg transition-all"
            >
                <i class="fas fa-plus-circle"></i>
                Déclarer un nouveau déchet
            </a>
        </div>
    </div>
</div>

<!-- Messages Flash -->
@if(session('success'))
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-success bg-opacity-10 border-l-4 border-success text-success px-6 py-4 rounded-lg flex items-center gap-3">
        <i class="fas fa-check-circle text-2xl"></i>
        <p class="font-medium">{{ session('success') }}</p>
    </div>
</div>
@endif

@if(session('error'))
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-lg flex items-center gap-3">
        <i class="fas fa-exclamation-circle text-2xl"></i>
        <p class="font-medium">{{ session('error') }}</p>
    </div>
</div>
@endif

<!-- Dechets Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($Dechets->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($Dechets as $dechet)
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg card-hover">
                    <!-- Image -->
                    <div class="relative h-56 overflow-hidden group">
                        @if($dechet->photo)
                            <img 
                                src="{{ asset('uploads/dechets/' . $dechet->photo) }}" 
                                alt="{{ $dechet->title }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            >
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                <i class="fas fa-image text-6xl text-gray-400"></i>
                            </div>
                        @endif
                        
                        <!-- Badge Statut -->
                        <div class="absolute top-4 right-4">
                            <span class="px-4 py-2 rounded-full text-xs font-semibold 
                                @if($dechet->status === 'available') bg-green-500
                                @elseif($dechet->status === 'reserved') bg-orange-500
                                @else bg-gray-500
                                @endif text-white backdrop-blur-sm">
                                @if($dechet->status === 'available') Disponible
                                @elseif($dechet->status === 'reserved') Réservé
                                @else {{ ucfirst($dechet->status) }}
                                @endif
                            </span>
                        </div>
                        
                        <!-- Badge Catégorie -->
                        <div class="absolute bottom-4 left-4">
                            <span class="px-4 py-2 rounded-full text-xs font-semibold bg-white text-primary backdrop-blur-sm flex items-center gap-2">
                                <i class="{{ $dechet->category->icon ?? 'fas fa-tag' }}"></i>
                                {{ $dechet->category->name }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                            {{ $dechet->title }}
                        </h3>
                        
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            {{ Str::limit($dechet->description, 100) }}
                        </p>
                        
                        <!-- Meta Info -->
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt text-accent"></i>
                                <span>{{ $dechet->location }}</span>
                            </div>
                            
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <i class="fas fa-box text-secondary"></i>
                                <span>Quantité: {{ $dechet->quantity }}</span>
                            </div>
                            
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <i class="fas fa-eye text-primary"></i>
                                <span>{{ $dechet->views_count ?? 0 }} vues</span>
                            </div>
                            
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <i class="fas fa-calendar text-primary"></i>
                                <span>{{ $dechet->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="grid grid-cols-3 gap-2">
                            <a 
                                href="{{ route('dechets.show', $dechet->id) }}" 
                                class="bg-primary hover:bg-green-700 text-white text-center py-2 rounded-lg font-medium transition-colors text-sm"
                                title="Voir"
                            >
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <a 
                                href="{{ route('dechets.edit', $dechet->id) }}" 
                                class="bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded-lg font-medium transition-colors text-sm"
                                title="Modifier"
                            >
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <form 
                                action="{{ route('dechets.destroy', $dechet->id) }}" 
                                method="POST" 
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce déchet ?')"
                            >
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit" 
                                    class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg font-medium transition-colors text-sm"
                                    title="Supprimer"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-12">
            {{ $Dechets->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                <i class="fas fa-box-open text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun déchet déclaré</h3>
            <p class="text-gray-600 mb-6">
                Vous n'avez pas encore déclaré de déchet. Commencez maintenant !
            </p>
            
            <a 
                href="{{ route('dechets.create') }}" 
                class="inline-flex items-center gap-2 bg-primary hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors"
            >
                <i class="fas fa-plus-circle"></i>
                Déclarer mon premier déchet
            </a>
        </div>
    @endif
</div>
@endsection