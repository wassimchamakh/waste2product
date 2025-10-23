@extends('FrontOffice.layout1.app')

@section('title', 'Déchets Disponibles - dechet2Product')

@section('content')
<!-- Hero Section -->
<div class="gradient-hero text-white py-16">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <i class="fas fa-recycle mr-3"></i>Déchets Disponibles
            </h1>
            <p class="text-xl opacity-90 mb-8">
                Donnez une seconde vie aux déchets. Ensemble pour une Tunisie plus verte !
            </p>
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto">
                <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-xl p-6">
                    <div class="text-3xl font-bold">{{ $stats['total'] }}</div>
                    <div class="text-sm opacity-90">Déchets actifs</div>
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
        <div class="mt-4 flex flex-wrap gap-3 justify-center">
    <a 
        href="{{ route('dechets.create') }}" 
        class="inline-flex items-center gap-2 bg-gradient-to-r from-secondary to-accent text-white px-6 py-3 rounded-lg font-medium hover:shadow-lg transition-all"
    >
        <i class="fas fa-plus-circle"></i>
        Déclarer un déchet
    </a>
    
    <a
        href="{{ route('dechets.my') }}"
        class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-700 text-white px-6 py-3 rounded-lg font-medium hover:shadow-lg transition-all"
    >
        <i class="fas fa-box-open"></i>
        Mes déchets
    </a>

    <a
        href="{{ route('dechets.favorites') }}"
        class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-500 to-red-600 text-white px-6 py-3 rounded-lg font-medium hover:shadow-lg transition-all"
    >
        <i class="fas fa-heart"></i>
        Mes favoris
    </a>
</div>
    </div>
</div>

<!-- Filters Section -->
<div class="bg-white shadow-lg sticky top-16 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <form method="GET" action="{{ route('dechets.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Recherche -->
                <div class="md:col-span-2">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Rechercher un déchet..." 
                            class="w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                
                <!-- Catégorie -->
                <div>
                    <select 
                        name="category" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent"
                    >
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->Dechets_count }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Statut -->
                <div>
                    <select 
                        name="status" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent"
                    >
                        <option value="">Tous les statuts</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Réservé</option>
                    </select>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex-1">
                    <input 
                        type="text" 
                        name="location" 
                        value="{{ request('location') }}"
                        placeholder="Localisation (ville, gouvernorat...)" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent"
                    >
                </div>
                
                <div class="flex gap-3 w-full sm:w-auto">
                    <button 
                        type="submit" 
                        class="flex-1 sm:flex-initial bg-primary hover:bg-green-700 text-white px-8 py-3 rounded-lg font-medium transition-colors flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-search"></i>
                        Filtrer
                    </button>
                    
                    <button 
                        type="button"
                        onclick="openImageRecognition()"
                        class="flex-1 sm:flex-initial bg-gradient-to-r from-purple-500 to-indigo-500 hover:shadow-lg text-white px-6 py-3 rounded-lg font-medium transition-all flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-camera"></i>
                        Reconnaître
                    </button>
                    
                    <a 
                        href="{{ route('dechets.index') }}" 
                        class="flex-1 sm:flex-initial bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium transition-colors"
                    >
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>
        
        @auth
        <div class="mt-4">
            <a 
                href="{{ route('dechets.create') }}" 
                class="inline-flex items-center gap-2 bg-gradient-to-r from-secondary to-accent text-white px-6 py-3 rounded-lg font-medium hover:shadow-lg transition-all"
            >
                <i class="fas fa-plus-circle"></i>
                Déclarer un déchet
            </a>
        </div>
        @endauth
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
                                src="{{ asset('uploads/Dechets/' . $dechet->photo) }}" 
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
                            <span class="px-4 py-2 rounded-full text-xs font-semibold {{ $dechet->getStatusBadgeClass() }} text-white backdrop-blur-sm">
                                {{ $dechet->getStatusLabel() }}
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

                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center gap-2 text-gray-600">
                                    <i class="fas fa-eye text-primary"></i>
                                    <span>{{ $dechet->views_count }} vues</span>
                                </div>

                                <!-- Favorites Counter -->
                                <div class="flex items-center gap-3 text-gray-500">
                                    @if($dechet->reviews_count > 0)
                                        <div class="flex items-center gap-1">
                                            <i class="fas fa-star text-yellow-400 text-xs"></i>
                                            <span class="text-xs font-semibold">{{ number_format($dechet->average_rating, 1) }}</span>
                                            <span class="text-xs">({{ $dechet->reviews_count }})</span>
                                        </div>
                                    @endif
                                    @if($dechet->favorites_count > 0)
                                        <div class="flex items-center gap-1">
                                            <i class="fas fa-heart text-red-400 text-xs"></i>
                                            <span class="text-xs font-semibold">{{ $dechet->favorites_count }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a
                                href="{{ route('dechets.show', $dechet->id) }}"
                                class="flex-1 bg-gradient-to-r from-primary to-green-600 hover:from-green-600 hover:to-primary text-white text-center py-3 rounded-lg font-medium transition-all transform hover:scale-105 shadow-md"
                            >
                                <i class="fas fa-eye mr-2"></i>Voir détails
                            </a>

                            <!-- Favorite Button -->
                            <button
                                onclick="toggleFavorite({{ $dechet->id }}, this)"
                                class="favorite-btn bg-gray-100 hover:bg-red-50 text-gray-600 p-3 rounded-lg transition-all transform hover:scale-110"
                                data-dechet-id="{{ $dechet->id }}"
                                title="Ajouter aux favoris"
                            >
                                <i class="fas fa-heart {{ $dechet->is_favorited > 0 ? 'text-red-500' : '' }}"></i>
                            </button>

                            @if($dechet->user_id === 4)
                                <a
                                    href="{{ route('dechets.edit', $dechet->id) }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-lg transition-colors"
                                    title="Modifier"
                                >
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif
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
                <i class="fas fa-search text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun déchet trouvé</h3>
            <p class="text-gray-600 mb-6">
                Essayez de modifier vos filtres ou 
                <a href="{{ route('dechets.index') }}" class="text-primary font-medium hover:underline">réinitialisez la recherche</a>
            </p>
            
            @auth
            <a 
                href="{{ route('dechets.create') }}" 
                class="inline-flex items-center gap-2 bg-primary hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors"
            >
                <i class="fas fa-plus-circle"></i>
                Être le premier à déclarer un déchet
            </a>
            @endauth
        </div>
    @endif
</div>

<!-- Favorite Toggle Script -->
<script>
function toggleFavorite(dechetId, button) {
    fetch(`/dechets/${dechetId}/favorite`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        const icon = button.querySelector('i');

        if (data.favorited) {
            icon.classList.add('text-red-500');
            button.classList.add('bg-red-50', 'dark:bg-red-900');
            button.classList.remove('bg-gray-100', 'dark:bg-gray-600');
        } else {
            icon.classList.remove('text-red-500');
            button.classList.remove('bg-red-50', 'dark:bg-red-900');
            button.classList.add('bg-gray-100', 'dark:bg-gray-600');
        }

        // Show toast notification
        showToast(data.message, data.favorited ? 'success' : 'info');
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Une erreur s\'est produite', 'error');
    });
}

function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-2xl transform transition-all duration-300 translate-x-full ${
        type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    } text-white font-medium flex items-center gap-3`;

    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} text-xl"></i>
        <span>${message}</span>
    `;

    document.body.appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);

    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
@endsection