@extends('BackOffice.layouts.app')

@section('title', 'Gérer les Posts - Forum Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">📝 Gérer les Posts</h1>
            <p class="text-gray-600">Modérer, épingler, verrouiller et supprimer les posts</p>
        </div>
        <a href="{{ route('admin.forum.index') }}" 
           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
            <i class="fas fa-arrow-left mr-2"></i> Retour
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.forum.posts') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Rechercher..."
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <select name="status" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
                    <option value="">Tous les statuts</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publié</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archivé</option>
                </select>
            </div>
            <div>
                <select name="spam" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
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

    <!-- Posts Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Post</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Auteur</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Stats</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Statut</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($posts as $post)
                        <tr class="{{ $post->is_spam ? 'bg-red-50' : '' }}">
                            <!-- Post Info -->
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex-1">
                                        <a href="{{ route('forum.posts.show', $post) }}" target="_blank"
                                           class="font-semibold text-gray-800 hover:text-primary">
                                            {{ Str::limit($post->title, 60) }}
                                        </a>
                                        <div class="flex items-center gap-2 mt-1">
                                            @if($post->is_pinned)
                                                <span class="text-xs bg-primary/10 text-primary px-2 py-1 rounded">
                                                    <i class="fas fa-thumbtack"></i> Épinglé
                                                </span>
                                            @endif
                                            @if($post->is_locked)
                                                <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded">
                                                    <i class="fas fa-lock"></i> Verrouillé
                                                </span>
                                            @endif
                                            @if($post->is_spam)
                                                <span class="text-xs bg-red-200 text-red-700 px-2 py-1 rounded">
                                                    <i class="fas fa-exclamation-triangle"></i> Spam
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">{{ $post->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Author -->
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <p class="font-semibold text-gray-800">{{ $post->user->name }}</p>
                                    <p class="text-gray-500 text-xs">🏆 {{ $post->user->reputation }} pts</p>
                                </div>
                            </td>

                            <!-- Stats -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-3 text-sm">
                                    <span class="text-success">
                                        <i class="fas fa-arrow-up"></i> {{ $post->upvotes }}
                                    </span>
                                    <span class="text-red-500">
                                        <i class="fas fa-arrow-down"></i> {{ $post->downvotes }}
                                    </span>
                                    <span class="text-gray-600">
                                        <i class="fas fa-comments"></i> {{ $post->all_comments_count }}
                                    </span>
                                    <span class="text-gray-600">
                                        <i class="fas fa-eye"></i> {{ $post->views_count }}
                                    </span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $post->getStatusBadgeClass() }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <!-- Pin/Unpin -->
                                    <form action="{{ route('admin.forum.posts.pin', $post) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-sm {{ $post->is_pinned ? 'text-primary' : 'text-gray-400' }} hover:text-primary"
                                                title="{{ $post->is_pinned ? 'Désépingler' : 'Épingler' }}">
                                            <i class="fas fa-thumbtack"></i>
                                        </button>
                                    </form>

                                    <!-- Lock/Unlock -->
                                    <form action="{{ route('admin.forum.posts.lock', $post) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-sm {{ $post->is_locked ? 'text-gray-700' : 'text-gray-400' }} hover:text-gray-700"
                                                title="{{ $post->is_locked ? 'Déverrouiller' : 'Verrouiller' }}">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    </form>

                                    <!-- Spam Toggle -->
                                    <form action="{{ route('admin.forum.spam.toggle') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="type" value="post">
                                        <input type="hidden" name="id" value="{{ $post->id }}">
                                        <button type="submit" 
                                                class="text-sm {{ $post->is_spam ? 'text-red-600' : 'text-gray-400' }} hover:text-red-600"
                                                title="{{ $post->is_spam ? 'Retirer spam' : 'Marquer spam' }}">
                                            <i class="fas fa-flag"></i>
                                        </button>
                                    </form>

                                    <!-- Delete -->
                                    <form action="{{ route('admin.forum.posts.destroy', $post) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Supprimer ce post?')"
                                                class="text-sm text-gray-400 hover:text-red-600"
                                                title="Supprimer">
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
                                <p>Aucun post trouvé</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
