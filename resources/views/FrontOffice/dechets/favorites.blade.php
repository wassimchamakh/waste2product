@extends('FrontOffice.layout1.app')

@section('title', 'Mes Favoris - Waste2Product')

@section('content')
<!-- Hero Section -->
<div class="gradient-hero text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <i class="fas fa-heart mr-3"></i>Mes Favoris
            </h1>
            <p class="text-xl opacity-90 mb-8">
                Retrouvez tous les déchets que vous avez sauvegardés
            </p>

            <!-- Quick Stats -->
            <div class="inline-flex items-center gap-6 bg-white bg-opacity-20 backdrop-blur-lg rounded-xl px-8 py-4">
                <div class="text-center">
                    <div class="text-3xl font-bold">{{ $favorites->total() }}</div>
                    <div class="text-sm opacity-90">Favoris</div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="mt-6 flex flex-wrap gap-3 justify-center">
            <a
                href="{{ route('dechets.index') }}"
                class="inline-flex items-center gap-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-3 rounded-lg font-medium transition-all"
            >
                <i class="fas fa-search"></i>
                Parcourir les déchets
            </a>

            <a
                href="{{ route('dechets.my') }}"
                class="inline-flex items-center gap-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-3 rounded-lg font-medium transition-all"
            >
                <i class="fas fa-box-open"></i>
                Mes déchets
            </a>
        </div>
    </div>
</div>

<!-- Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($favorites->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($favorites as $dechet)
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg card-hover relative">
                    <!-- Remove from Favorites Badge -->
                    <button
                        onclick="toggleFavorite({{ $dechet->id }}, this)"
                        class="absolute top-4 right-4 z-10 bg-red-500 hover:bg-red-600 text-white p-3 rounded-full shadow-lg transition-all transform hover:scale-110"
                        data-dechet-id="{{ $dechet->id }}"
                        title="Retirer des favoris"
                    >
                        <i class="fas fa-heart"></i>
                    </button>

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

                        <!-- Status Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="px-4 py-2 rounded-full text-xs font-semibold {{ $dechet->getStatusBadgeClass() }} text-white backdrop-blur-sm">
                                {{ $dechet->getStatusLabel() }}
                            </span>
                        </div>

                        <!-- Category Badge -->
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

                                <!-- Rating -->
                                @if($dechet->reviews_count > 0)
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                                        <span class="text-xs font-semibold">{{ number_format($dechet->average_rating, 1) }}</span>
                                        <span class="text-xs text-gray-500">({{ $dechet->reviews_count }})</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a
                                href="{{ route('dechets.show', $dechet->id) }}"
                                class="flex-1 bg-primary hover:bg-green-700 text-white text-center py-3 rounded-lg font-medium transition-all transform hover:scale-105"
                            >
                                <i class="fas fa-eye mr-2"></i>Voir détails
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $favorites->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-20">
            <div class="inline-flex items-center justify-center w-32 h-32 bg-gradient-to-br from-pink-100 to-red-100 rounded-full mb-8">
                <i class="fas fa-heart text-6xl text-pink-400"></i>
            </div>

            <h3 class="text-3xl font-bold text-gray-900 mb-4">
                Aucun favori pour le moment
            </h3>

            <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">
                Commencez à sauvegarder des déchets qui vous intéressent en cliquant sur le cœur
                <i class="fas fa-heart text-red-500"></i>
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a
                    href="{{ route('dechets.index') }}"
                    class="inline-flex items-center justify-center gap-3 bg-gradient-to-r from-primary to-green-600 hover:from-green-600 hover:to-primary text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1"
                >
                    <i class="fas fa-search"></i>
                    Parcourir les déchets
                </a>

                <a
                    href="{{ route('dechets.create') }}"
                    class="inline-flex items-center justify-center gap-3 bg-gradient-to-r from-secondary to-accent hover:from-accent hover:to-secondary text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1"
                >
                    <i class="fas fa-plus-circle"></i>
                    Déclarer un déchet
                </a>
            </div>
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
        if (!data.favorited) {
            // Remove card with animation
            const card = button.closest('.card-hover');
            card.style.transform = 'scale(0.8)';
            card.style.opacity = '0';

            setTimeout(() => {
                card.remove();

                // Check if no more favorites
                const grid = document.querySelector('.grid');
                if (grid && grid.children.length === 0) {
                    location.reload(); // Reload to show empty state
                }
            }, 300);

            showToast('Retiré des favoris', 'info');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Une erreur s\'est produite', 'error');
    });
}

function showToast(message, type = 'info') {
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

    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);

    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
@endsection
