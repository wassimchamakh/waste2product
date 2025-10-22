@extends('BackOffice.layouts.app')

@section('title', 'Gérer les Commentaires - Forum Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">💬 Gérer les Commentaires</h1>
            <p class="text-gray-600">Modérer, supprimer et filtrer les commentaires</p>
        </div>
        <a href="{{ route('admin.forum.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
            <i class="fas fa-arrow-left mr-2"></i> Retour
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.forum.comments') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <select name="best_answer" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
                    <option value="">Meilleure réponse: Tous</option>
                    <option value="yes" {{ request('best_answer') == 'yes' ? 'selected' : '' }}>Oui</option>
                    <option value="no" {{ request('best_answer') == 'no' ? 'selected' : '' }}>Non</option>
                </select>
            </div>
            <div>
                <select name="spam" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
                    <option value="">Spam: Tous</option>
                    <option value="yes" {{ request('spam') == 'yes' ? 'selected' : '' }}>Spam uniquement</option>
                    <option value="no" {{ request('spam') == 'no' ? 'selected' : '' }}>Pas de spam</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">
                    <i class="fas fa-filter mr-2"></i> Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Commentaire</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Auteur</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Post</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Votes</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($comments as $comment)
                        <tr class="{{ $comment->is_spam ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-800">{{ Str::limit($comment->body, 120) }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $comment->created_at->format('d/m/Y H:i') }}</p>
                                @if($comment->is_best_answer)
                                    <span class="inline-flex items-center px-2 py-1 text-xs text-success bg-success/10 rounded">
                                        <i class="fas fa-check-circle mr-1"></i> Meilleure réponse
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-gray-800">{{ $comment->user->name }}</p>
                                <p class="text-xs text-gray-500">🏆 {{ $comment->user->reputation }} pts</p>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('forum.posts.show', $comment->forumPost) }}" target="_blank" class="text-sm text-primary hover:underline">
                                    {{ Str::limit($comment->forumPost->title, 40) }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-success text-sm"><i class="fas fa-arrow-up"></i> {{ $comment->upvotes }}</span>
                                <span class="text-gray-400 mx-1">|</span>
                                <span class="text-red-500 text-sm"><i class="fas fa-arrow-down"></i> {{ $comment->downvotes }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <!-- Spam Toggle -->
                                    <form action="{{ route('admin.forum.spam.toggle') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="type" value="comment">
                                        <input type="hidden" name="id" value="{{ $comment->id }}">
                                        <button type="submit" class="text-sm {{ $comment->is_spam ? 'text-red-600' : 'text-gray-400' }} hover:text-red-600" title="{{ $comment->is_spam ? 'Retirer spam' : 'Marquer spam' }}">
                                            <i class="fas fa-flag"></i>
                                        </button>
                                    </form>

                                    <!-- Delete -->
                                    <form action="{{ route('admin.forum.comments.destroy', $comment) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Supprimer ce commentaire?')" class="text-sm text-gray-400 hover:text-red-600" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Aucun commentaire trouvé</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($comments->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $comments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
