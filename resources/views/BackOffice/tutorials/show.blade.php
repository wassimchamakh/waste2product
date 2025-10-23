@extends('BackOffice.layouts.app')

@section('title', 'Détails du Tutoriel')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tutorials.index') }}" class="text-gray-600 hover:text-gray-800">
                ← Retour
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Détails du Tutoriel</h1>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('tutorials.show', $tutorial->slug) }}" target="_blank"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                👁️ Voir en ligne
            </a>
            <a href="{{ route('admin.tutorials.edit', $tutorial->id) }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                ✏️ Modifier
            </a>
            <form method="POST" action="{{ route('admin.tutorials.destroy', $tutorial->id) }}" class="inline"
                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce tutoriel ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    🗑️ Supprimer
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Tutorial Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex gap-6">
                    <img src="{{ $tutorial->thumbnail_url }}" alt="{{ $tutorial->title }}"
                        class="w-48 h-48 object-cover rounded-lg shadow-lg">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $tutorial->title }}</h2>
                        <p class="text-gray-600 mb-4">{{ $tutorial->description }}</p>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <span class="text-sm text-gray-600">Auteur:</span>
                                <div class="font-semibold">{{ $tutorial->creator->name }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Niveau:</span>
                                <div class="font-semibold capitalize">{{ $tutorial->difficulty_level }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Durée estimée:</span>
                                <div class="font-semibold">{{ $tutorial->estimated_duration }} min</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Catégorie:</span>
                                <div class="font-semibold">{{ $tutorial->category }}</div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.tutorials.status', $tutorial->id) }}">
                                @csrf
                                <input type="hidden" name="status" value="{{ $tutorial->status === 'Published' ? 'Draft' : 'Published' }}">
                                @if($tutorial->status === 'Published')
                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">
                                    📝 Mettre en brouillon
                                </button>
                                @else
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm">
                                    ✓ Publier
                                </button>
                                @endif
                            </form>

                            <form method="POST" action="{{ route('admin.tutorials.featured', $tutorial->id) }}">
                                @csrf
                                @if($tutorial->is_featured)
                                <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
                                    Retirer de vedette
                                </button>
                                @else
                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">
                                    ⭐ Mettre en vedette
                                </button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Statistiques</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-3xl font-bold text-blue-600">{{ number_format($totalViews) }}</div>
                        <div class="text-sm text-gray-600 mt-1">Vues Totales</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-3xl font-bold text-green-600">{{ number_format($completionRate, 1) }}%</div>
                        <div class="text-sm text-gray-600 mt-1">Taux de Complétion</div>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <div class="text-3xl font-bold text-yellow-600">{{ number_format($avgRating, 1) }}</div>
                        <div class="text-sm text-gray-600 mt-1">Note Moyenne</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-3xl font-bold text-purple-600">{{ $totalProgress }}</div>
                        <div class="text-sm text-gray-600 mt-1">Utilisateurs Actifs</div>
                    </div>
                </div>
            </div>

            <!-- Recent Comments -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Commentaires Récents</h3>
                    <a href="{{ route('admin.tutorials.comments', $tutorial->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        Voir tous →
                    </a>
                </div>
                @forelse($recentComments as $comment)
                <div class="border-b last:border-b-0 py-3">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="font-semibold">{{ $comment->user->name }}</span>
                            <span class="text-gray-500 text-sm ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        @if($comment->status === 'pending')
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">En attente</span>
                        @endif
                    </div>
                    <p class="text-gray-700">{{ Str::limit($comment->content, 150) }}</p>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Aucun commentaire</p>
                @endforelse
            </div>
        </div>

        <!-- Right Column: Quick Stats and Actions -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Actions Rapides</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.tutorials.steps', $tutorial->id) }}"
                        class="block w-full bg-purple-100 hover:bg-purple-200 text-purple-800 px-4 py-3 rounded-lg text-center transition">
                        📋 Gérer les Étapes ({{ $stepsCount }})
                    </a>
                    <a href="{{ route('admin.tutorials.comments', $tutorial->id) }}"
                        class="block w-full bg-indigo-100 hover:bg-indigo-200 text-indigo-800 px-4 py-3 rounded-lg text-center transition">
                        💬 Modérer les Commentaires ({{ $commentsCount }})
                    </a>
                    <a href="{{ route('admin.tutorials.progress', $tutorial->id) }}"
                        class="block w-full bg-cyan-100 hover:bg-cyan-200 text-cyan-800 px-4 py-3 rounded-lg text-center transition">
                        📊 Voir la Progression ({{ $totalProgress }} users)
                    </a>
                </div>
            </div>

            <!-- Status Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Informations</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Statut:</span>
                        @if($tutorial->status === 'Published')
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                            ✓ Publié
                        </span>
                        @else
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                            📝 Brouillon
                        </span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Vedette:</span>
                        <span class="font-semibold">{{ $tutorial->is_featured ? '⭐ Oui' : 'Non' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Créé le:</span>
                        <span class="font-semibold">{{ $tutorial->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Mis à jour:</span>
                        <span class="font-semibold">{{ $tutorial->updated_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Slug:</span>
                        <span class="font-mono text-sm text-gray-600">{{ $tutorial->slug }}</span>
                    </div>
                </div>
            </div>

            <!-- Top Users Progress -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Meilleurs Utilisateurs</h3>
                @forelse($topUsers as $progress)
                <div class="flex items-center justify-between py-2 border-b last:border-b-0">
                    <div>
                        <div class="font-semibold text-sm">{{ $progress->user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $progress->completed_steps }}/{{ $stepsCount }} étapes</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-green-600">{{ number_format($progress->progress_percentage, 0) }}%</div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4 text-sm">Aucun utilisateur actif</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
