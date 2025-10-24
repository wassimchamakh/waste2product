@extends('FrontOffice.layout1.app')

@section('title', $Dechet->title . ' - Dechet2Product')

@section('content')
<!-- Hero Section with Navigation -->
<<<<<<< HEAD
<div class="bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 mb-6">
=======
<div class="bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
>>>>>>> tutoral-branch
            <a href="{{ route('dechets.index') }}" class="hover:text-primary transition-colors">
                <i class="fas fa-home mr-1"></i>Déchets
            </a>
            <i class="fas fa-chevron-right text-xs"></i>
<<<<<<< HEAD
            <span class="text-gray-900 dark:text-white">{{ Str::limit($Dechet->title, 30) }}</span>
=======
            <span class="text-gray-900">{{ Str::limit($Dechet->title, 30) }}</span>
>>>>>>> tutoral-branch
        </nav>
        
        <!-- Quick Actions -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a 
                    href="{{ route('dechets.index') }}" 
<<<<<<< HEAD
                    class="inline-flex items-center gap-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm border border-gray-200 dark:border-gray-700"
=======
                    class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors shadow-sm border border-gray-200"
>>>>>>> tutoral-branch
                >
                    <i class="fas fa-arrow-left"></i>
                    Retour à la liste
                </a>
            </div>
            
            @if($Dechet->user_id === 4)
            <div class="flex items-center gap-3">
                <a 
                    href="{{ route('dechets.edit', $Dechet->id) }}" 
                    class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                >
                    <i class="fas fa-edit"></i>
                    Modifier
                </a>
                <button 
                    onclick="showDeleteModal()" 
                    class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                >
                    <i class="fas fa-trash"></i>
                    Supprimer
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Main Content Column -->
        <div class="lg:col-span-2">
<<<<<<< HEAD
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
=======
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
>>>>>>> tutoral-branch
                
                <!-- Image Section -->
                <div class="relative h-96 md:h-[28rem] overflow-hidden">
                    @if($Dechet->photo)
                        <img 
                            src="{{ asset('uploads/dechets/' . $Dechet->photo) }}" 
                            alt="{{ $Dechet->title }}"
                            class="w-full h-full object-cover"
                        >
                    @else
<<<<<<< HEAD
                        <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-image text-8xl text-gray-400 mb-4"></i>
                                <p class="text-gray-500 dark:text-gray-400 text-lg">Aucune photo disponible</p>
=======
                        <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-image text-8xl text-gray-400 mb-4"></i>
                                <p class="text-gray-500 text-lg">Aucune photo disponible</p>
>>>>>>> tutoral-branch
                            </div>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="absolute top-6 right-6">
                        <span class="px-6 py-3 rounded-full text-sm font-bold 
                            @if($Dechet->status === 'available') bg-green-500
                            @elseif($Dechet->status === 'reserved') bg-orange-500
                            @else bg-gray-500
                            @endif text-white shadow-lg backdrop-blur-sm">
                            @if($Dechet->status === 'available') 
                                <i class="fas fa-check-circle mr-2"></i>Disponible
                            @elseif($Dechet->status === 'reserved') 
                                <i class="fas fa-clock mr-2"></i>Réservé
                            @else 
                                {{ ucfirst($Dechet->status) }}
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="p-8">
                    <!-- Header -->
                    <div class="mb-8">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
<<<<<<< HEAD
                                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3">
=======
                                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
>>>>>>> tutoral-branch
                                    {{ $Dechet->title }}
                                </h1>
                                
                                <!-- Category Badge -->
                                <div class="inline-flex items-center gap-2 bg-primary bg-opacity-10 text-primary px-4 py-2 rounded-full">
                                    <i class="{{ $Dechet->category->icon ?? 'fas fa-tag' }}"></i>
                                    <span class="font-medium">{{ $Dechet->category->name }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Meta Information -->
<<<<<<< HEAD
                        <div class="flex flex-wrap items-center gap-6 text-sm text-gray-600 dark:text-gray-400">
=======
                        <div class="flex flex-wrap items-center gap-6 text-sm text-gray-600">
>>>>>>> tutoral-branch
                            <div class="flex items-center gap-2">
                                <i class="fas fa-calendar text-primary"></i>
                                <span>{{ $Dechet->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-eye text-primary"></i>
                                <span>{{ $Dechet->views_count ?? 0 }} vues</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-clock text-primary"></i>
                                <span>Publié {{ $Dechet->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-8">
<<<<<<< HEAD
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-primary"></i>
                            Description
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $Dechet->description }}</p>
=======
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-primary"></i>
                            Description
                        </h2>
                        <div class="prose prose-gray max-w-none">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $Dechet->description }}</p>
>>>>>>> tutoral-branch
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
<<<<<<< HEAD
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6">
=======
                        <div class="bg-gray-50 rounded-xl p-6">
>>>>>>> tutoral-branch
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-secondary rounded-lg flex items-center justify-center">
                                    <i class="fas fa-box text-white"></i>
                                </div>
<<<<<<< HEAD
                                <h3 class="font-bold text-gray-900 dark:text-white">Quantité</h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 text-lg">{{ $Dechet->quantity }}</p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6">
=======
                                <h3 class="font-bold text-gray-900">Quantité</h3>
                            </div>
                            <p class="text-gray-600 text-lg">{{ $Dechet->quantity }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-6">
>>>>>>> tutoral-branch
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt text-white"></i>
                                </div>
<<<<<<< HEAD
                                <h3 class="font-bold text-gray-900 dark:text-white">Localisation</h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 text-lg">{{ $Dechet->location }}</p>
=======
                                <h3 class="font-bold text-gray-900">Localisation</h3>
                            </div>
                            <p class="text-gray-600 text-lg">{{ $Dechet->location }}</p>
>>>>>>> tutoral-branch
                        </div>
                    </div>

                    <!-- Contact Actions -->
                    @if($Dechet->user_id !== 4 && $Dechet->status === 'available')
                    <div class="bg-gradient-to-r from-primary to-green-700 rounded-xl p-6 text-white">
                        <h3 class="text-xl font-bold mb-3 flex items-center gap-2">
                            <i class="fas fa-handshake"></i>
                            Intéressé par ce déchet ?
                        </h3>
                        <p class="mb-4 opacity-90">Contactez le propriétaire pour organiser la récupération</p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button class="flex-1 bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-3 rounded-lg font-medium transition-all backdrop-blur-sm">
                                <i class="fas fa-envelope mr-2"></i>
                                Envoyer un message
                            </button>
                            <form action="{{ route('dechets.reserve', $Dechet->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button 
                                    type="submit" 
                                    class="w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-3 rounded-lg font-medium transition-all backdrop-blur-sm"
                                    onclick="return confirm('Voulez-vous réserver ce déchet ?')"
                                >
                                    <i class="fas fa-bookmark mr-2"></i>
                                    Réserver
                                </button>
                            </form>
                        </div>
                    </div>
                    @elseif($Dechet->status === 'reserved')
<<<<<<< HEAD
                    <div class="bg-orange-50 dark:bg-orange-900 dark:bg-opacity-20 border border-orange-200 dark:border-orange-800 rounded-xl p-6">
                        <div class="flex items-center gap-3 text-orange-700 dark:text-orange-300">
=======
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-6">
                        <div class="flex items-center gap-3 text-orange-700">
>>>>>>> tutoral-branch
                            <i class="fas fa-info-circle text-2xl"></i>
                            <div>
                                <h3 class="font-bold text-lg">Déchet réservé</h3>
                                <p>Ce déchet a été réservé par un autre utilisateur.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- User Info Card -->
<<<<<<< HEAD
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
=======
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
>>>>>>> tutoral-branch
                    <i class="fas fa-user text-primary"></i>
                    Publié par
                </h3>
                
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary to-green-700 rounded-full flex items-center justify-center text-white font-bold text-xl">
                        {{ strtoupper(substr($Dechet->user->name, 0, 1)) }}
                    </div>
                    <div>
<<<<<<< HEAD
                        <h4 class="font-bold text-gray-900 dark:text-white text-lg">{{ $Dechet->user->name }}</h4>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
=======
                        <h4 class="font-bold text-gray-900 text-lg">{{ $Dechet->user->name }}</h4>
                        <p class="text-gray-600 text-sm">
>>>>>>> tutoral-branch
                            Membre depuis {{ $Dechet->user->created_at->format('Y') }}
                        </p>
                    </div>
                </div>
                
                <!-- User Stats -->
<<<<<<< HEAD
                <div class="grid grid-cols-2 gap-4 pt-4 border-t dark:border-gray-700">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary">{{ $Dechet->user->dechets()->where('is_active', true)->count() }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Déchets publiés</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-secondary">{{ $Dechet->user->created_at->diffInMonths() ?? 0 }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Mois d'ancienneté</div>
=======
                <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary">{{ $Dechet->user->dechets()->where('is_active', true)->count() }}</div>
                        <div class="text-xs text-gray-600">Déchets publiés</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-secondary">{{ $Dechet->user->created_at->diffInMonths() ?? 0 }}</div>
                        <div class="text-xs text-gray-600">Mois d'ancienneté</div>
>>>>>>> tutoral-branch
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-gradient-to-br from-primary to-green-700 text-white rounded-2xl p-6">
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-bar"></i>
                    Statistiques
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="opacity-90">Vues totales</span>
                        <span class="font-bold text-xl">{{ $Dechet->views_count ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="opacity-90">Publié depuis</span>
                        <span class="font-bold">{{ $Dechet->created_at->diffInDays() }} jours</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="opacity-90">Statut</span>
                        <span class="font-bold">
                            @if($Dechet->status === 'available') Disponible
                            @elseif($Dechet->status === 'reserved') Réservé
                            @else {{ ucfirst($Dechet->status) }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Share -->
<<<<<<< HEAD
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
=======
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
>>>>>>> tutoral-branch
                    <i class="fas fa-share-alt text-primary"></i>
                    Partager
                </h3>
                
                <div class="flex gap-3">
                    <a 
                        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" 
                        target="_blank"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg text-center transition-colors"
                        title="Partager sur Facebook"
                    >
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a 
                        href="https://twitter.com/intent/tweet?text={{ urlencode($Dechet->title) }}&url={{ urlencode(request()->fullUrl()) }}" 
                        target="_blank"
                        class="flex-1 bg-sky-500 hover:bg-sky-600 text-white p-3 rounded-lg text-center transition-colors"
                        title="Partager sur Twitter"
                    >
                        <i class="fab fa-twitter"></i>
                    </a>
                    <button 
                        onclick="copyToClipboard()" 
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white p-3 rounded-lg text-center transition-colors"
                        title="Copier le lien"
                    >
                        <i class="fas fa-link"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Similar Items Section -->
    @if($similarDechets->count() > 0)
    <div class="mt-16">
        <div class="text-center mb-12">
<<<<<<< HEAD
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                Déchets similaires
            </h2>
            <p class="text-gray-600 dark:text-gray-400 text-lg">
=======
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Déchets similaires
            </h2>
            <p class="text-gray-600 text-lg">
>>>>>>> tutoral-branch
                Découvrez d'autres déchets de la même catégorie
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($similarDechets as $similar)
<<<<<<< HEAD
                <div class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-lg card-hover">
=======
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg card-hover">
>>>>>>> tutoral-branch
                    <div class="h-48 overflow-hidden">
                        @if($similar->photo)
                            <img 
                                src="{{ asset('uploads/dechets/' . $similar->photo) }}" 
                                alt="{{ $similar->title }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                            >
                        @else
<<<<<<< HEAD
                            <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
=======
                            <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
>>>>>>> tutoral-branch
                                <i class="fas fa-image text-4xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-6">
<<<<<<< HEAD
                        <h3 class="font-bold text-gray-900 dark:text-white mb-2 line-clamp-1">
                            {{ $similar->title }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">
=======
                        <h3 class="font-bold text-gray-900 mb-2 line-clamp-1">
                            {{ $similar->title }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
>>>>>>> tutoral-branch
                            {{ Str::limit($similar->description, 80) }}
                        </p>
                        
                        <div class="flex items-center justify-between">
<<<<<<< HEAD
                            <span class="text-sm text-gray-500 dark:text-gray-400">
=======
                            <span class="text-sm text-gray-500">
>>>>>>> tutoral-branch
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $similar->location }}
                            </span>
                            <a 
                                href="{{ route('dechets.show', $similar->id) }}" 
                                class="bg-primary hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm"
                            >
                                Voir
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
@if($Dechet->user_id === 4)
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
            <h3 class="text-xl font-bold text-gray-900 mb-2">Confirmer la suppression</h3>
            <p class="text-gray-600">
>>>>>>> tutoral-branch
                Êtes-vous sûr de vouloir supprimer ce déchet ? Cette action est irréversible.
            </p>
        </div>
        
        <div class="flex gap-4">
            <button 
                onclick="hideDeleteModal()" 
<<<<<<< HEAD
                class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-white px-6 py-3 rounded-lg font-medium transition-colors"
=======
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium transition-colors"
>>>>>>> tutoral-branch
            >
                Annuler
            </button>
            <form action="{{ route('dechets.destroy', $Dechet->id) }}" method="POST" class="flex-1">
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

<!-- Flash Messages -->
@if(session('success'))
<div id="flash-message" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 transform translate-x-full opacity-0 transition-all duration-300">
    <div class="flex items-center gap-3">
        <i class="fas fa-check-circle text-xl"></i>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

@if(session('error'))
<div id="flash-message" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 transform translate-x-full opacity-0 transition-all duration-300">
    <div class="flex items-center gap-3">
        <i class="fas fa-exclamation-circle text-xl"></i>
        <span>{{ session('error') }}</span>
    </div>
</div>
@endif

<script>
// Show delete modal
function showDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

// Hide delete modal
function hideDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Copy to clipboard
function copyToClipboard() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        // Show temporary success message
        const button = event.target.closest('button');
        const original = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.classList.remove('bg-gray-500', 'hover:bg-gray-600');
        button.classList.add('bg-green-500');
        
        setTimeout(() => {
            button.innerHTML = original;
            button.classList.remove('bg-green-500');
            button.classList.add('bg-gray-500', 'hover:bg-gray-600');
        }, 2000);
    });
}

// Show flash messages
document.addEventListener('DOMContentLoaded', function() {
    const flashMessage = document.getElementById('flash-message');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.classList.remove('translate-x-full', 'opacity-0');
        }, 100);
        
        setTimeout(() => {
            flashMessage.classList.add('translate-x-full', 'opacity-0');
        }, 4000);
    }
});

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('deleteModal');
    if (e.target === modal) {
        hideDeleteModal();
    }
});
</script>
@endsection