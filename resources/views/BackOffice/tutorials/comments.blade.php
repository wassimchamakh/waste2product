@extends('BackOffice.layouts.app')

@section('title', 'Modération des Commentaires')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tutorials.show', $tutorial->id) }}" class="text-gray-600 hover:text-gray-800">
                ← Retour
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Modération des Commentaires</h1>
                <p class="text-gray-600 mt-1">{{ $tutorial->title }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Total Commentaires</div>
            <div class="text-3xl font-bold text-blue-600">{{ $totalComments }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Approuvés</div>
            <div class="text-3xl font-bold text-green-600">{{ $approvedCount }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">En Attente</div>
            <div class="text-3xl font-bold text-yellow-600">{{ $pendingCount }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Rejetés</div>
            <div class="text-3xl font-bold text-red-600">{{ $rejectedCount }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('admin.tutorials.comments', $tutorial->id) }}" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filtrer par statut</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Auteur, contenu..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                🔍 Filtrer
            </button>
        </form>
    </div>

    <!-- Comments List -->
    <div class="space-y-4">
        @forelse($comments as $comment)
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex gap-4">
                <!-- User Avatar -->
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 text-white rounded-full flex items-center justify-center text-xl font-bold">
                        {{ substr($comment->user->name, 0, 1) }}
                    </div>
                </div>

                <!-- Comment Content -->
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-gray-800">{{ $comment->user->name }}</span>
                                <span class="text-sm text-gray-500">{{ $comment->user->email }}</span>
                                @if($comment->status === 'approved')
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">
                                    ✓ Approuvé
                                </span>
                                @elseif($comment->status === 'pending')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-medium">
                                    ⏳ En attente
                                </span>
                                @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full font-medium">
                                    ✗ Rejeté
                                </span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-500 mt-1">
                                {{ $comment->created_at->format('d/m/Y à H:i') }} • {{ $comment->created_at->diffForHumans() }}
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="flex gap-2">
                            @if($comment->status !== 'approved')
                            <form method="POST" action="{{ route('admin.tutorials.comments.moderate', $comment->id) }}" class="inline">
                                @csrf
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="text-green-600 hover:text-green-800 text-lg" title="Approuver">
                                    ✓
                                </button>
                            </form>
                            @endif

                            @if($comment->status !== 'rejected')
                            <form method="POST" action="{{ route('admin.tutorials.comments.moderate', $comment->id) }}" class="inline">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="text-red-600 hover:text-red-800 text-lg" title="Rejeter">
                                    ✗
                                </button>
                            </form>
                            @endif

                            <form method="POST" action="{{ route('admin.tutorials.comments.moderate', $comment->id) }}" class="inline"
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')">
                                @csrf
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="text-gray-600 hover:text-gray-800 text-lg" title="Supprimer">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Comment Text -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-3">
                        <p class="text-gray-800">{{ $comment->content }}</p>
                    </div>

                    <!-- Comment Meta -->
                    <div class="flex gap-6 text-sm text-gray-600">
                        @if($comment->rating)
                        <div class="flex items-center">
                            <span class="mr-2">⭐</span>
                            <span>{{ $comment->rating }}/5</span>
                        </div>
                        @endif

                        <div class="flex items-center">
                            <span class="mr-2">👍</span>
                            <span>{{ $comment->helpful_count ?? 0 }} personnes ont trouvé cela utile</span>
                        </div>

                        @if($comment->parent_id)
                        <div class="flex items-center">
                            <span class="mr-2">↩️</span>
                            <span>Réponse</span>
                        </div>
                        @endif
                    </div>

                    <!-- Replies -->
                    @if($comment->replies && $comment->replies->count() > 0)
                    <div class="mt-4 pl-6 border-l-2 border-gray-300 space-y-3">
                        @foreach($comment->replies as $reply)
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="font-semibold text-gray-800">{{ $reply->user->name }}</span>
                                    <span class="text-xs text-gray-500 ml-2">{{ $reply->created_at->diffForHumans() }}</span>
                                    @if($reply->status === 'pending')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded ml-2">En attente</span>
                                    @endif
                                </div>
                                <div class="flex gap-2">
                                    @if($reply->status !== 'approved')
                                    <form method="POST" action="{{ route('admin.tutorials.comments.moderate', $reply->id) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="text-green-600 hover:text-green-800 text-sm">✓</button>
                                    </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.tutorials.comments.moderate', $reply->id) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="text-gray-600 hover:text-gray-800 text-sm">🗑️</button>
                                    </form>
                                </div>
                            </div>
                            <p class="text-gray-700 text-sm">{{ $reply->content }}</p>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Moderation Actions (Expanded) -->
                    <div class="mt-4 pt-4 border-t flex gap-3">
                        @if($comment->status === 'pending')
                        <form method="POST" action="{{ route('admin.tutorials.comments.moderate', $comment->id) }}" class="inline">
                            @csrf
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition">
                                ✓ Approuver
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.tutorials.comments.moderate', $comment->id) }}" class="inline">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition">
                                ✗ Rejeter
                            </button>
                        </form>
                        @elseif($comment->status === 'approved')
                        <form method="POST" action="{{ route('admin.tutorials.comments.moderate', $comment->id) }}" class="inline">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm transition">
                                Retirer l'approbation
                            </button>
                        </form>
                        @else
                        <form method="POST" action="{{ route('admin.tutorials.comments.moderate', $comment->id) }}" class="inline">
                            @csrf
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition">
                                Réapprouver
                            </button>
                        </form>
                        @endif

                        <form method="POST" action="{{ route('admin.tutorials.comments.moderate', $comment->id) }}" class="inline"
                            onsubmit="return confirm('Supprimer définitivement ce commentaire ?')">
                            @csrf
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm transition">
                                🗑️ Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <div class="text-5xl mb-3">💬</div>
            <div class="text-lg text-gray-600">Aucun commentaire trouvé</div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $comments->links() }}
    </div>
</div>
@endsection
