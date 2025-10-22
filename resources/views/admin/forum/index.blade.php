@extends('BackOffice.layouts.app')

@section('title', 'Forum - Administration')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">💬 Modération du Forum</h1>
        <p class="text-gray-600">Gérez les posts, commentaires et surveillez l'activité</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Posts</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_posts'] }}</p>
                    <p class="text-sm text-success mt-1">{{ $stats['published_posts'] }} publiés</p>
                </div>
                <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-comments text-primary text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Commentaires</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_comments'] }}</p>
                </div>
                <div class="w-12 h-12 bg-success/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-comment text-success text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Spam Détecté</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['spam_posts'] + $stats['spam_comments'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $stats['spam_posts'] }} posts, {{ $stats['spam_comments'] }} comments</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Utilisateurs Actifs</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $stats['pinned_posts'] }} épinglés, {{ $stats['locked_posts'] }} verrouillés</p>
                </div>
                <div class="w-12 h-12 bg-secondary/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-secondary text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('admin.forum.posts') }}" 
           class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition">
            <i class="fas fa-clipboard-list text-primary text-3xl mb-3"></i>
            <h3 class="font-bold text-gray-800 mb-2">Gérer les Posts</h3>
            <p class="text-gray-600 text-sm">Modérer, épingler, verrouiller les posts</p>
        </a>

        <a href="{{ route('admin.forum.comments') }}" 
           class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition">
            <i class="fas fa-comments text-success text-3xl mb-3"></i>
            <h3 class="font-bold text-gray-800 mb-2">Gérer les Commentaires</h3>
            <p class="text-gray-600 text-sm">Modérer et supprimer les commentaires</p>
        </a>

        <a href="{{ route('admin.forum.users') }}" 
           class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition">
            <i class="fas fa-user-shield text-secondary text-3xl mb-3"></i>
            <h3 class="font-bold text-gray-800 mb-2">Utilisateurs</h3>
            <p class="text-gray-600 text-sm">Voir la réputation et l'activité</p>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Spam Queue -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                File d'attente Spam
            </h2>

            @if($spamPosts->count() > 0 || $spamComments->count() > 0)
                <!-- Spam Posts -->
                @if($spamPosts->count() > 0)
                    <div class="mb-4">
                        <h3 class="font-semibold text-gray-700 mb-2">Posts signalés ({{ $spamPosts->count() }})</h3>
                        <div class="space-y-2">
                            @foreach($spamPosts as $post)
                                <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <a href="{{ route('forum.posts.show', $post) }}" target="_blank"
                                               class="font-semibold text-gray-800 hover:text-primary">
                                                {{ Str::limit($post->title, 50) }}
                                            </a>
                                            <p class="text-sm text-gray-600 mt-1">Par {{ $post->user->name }}</p>
                                        </div>
                                        <form action="{{ route('admin.forum.spam.toggle') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="type" value="post">
                                            <input type="hidden" name="id" value="{{ $post->id }}">
                                            <button type="submit" class="text-sm text-success hover:underline">
                                                Retirer spam
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Spam Comments -->
                @if($spamComments->count() > 0)
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Commentaires signalés ({{ $spamComments->count() }})</h3>
                        <div class="space-y-2">
                            @foreach($spamComments as $comment)
                                <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-800">{{ Str::limit($comment->body, 60) }}</p>
                                            <p class="text-xs text-gray-600 mt-1">
                                                Par {{ $comment->user->name }} sur 
                                                <a href="{{ route('forum.posts.show', $comment->forumPost) }}" target="_blank" class="text-primary hover:underline">
                                                    {{ Str::limit($comment->forumPost->title, 30) }}
                                                </a>
                                            </p>
                                        </div>
                                        <form action="{{ route('admin.forum.spam.toggle') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="type" value="comment">
                                            <input type="hidden" name="id" value="{{ $comment->id }}">
                                            <button type="submit" class="text-sm text-success hover:underline">
                                                Retirer spam
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <i class="fas fa-check-circle text-success text-4xl mb-2"></i>
                    <p class="text-gray-600">Aucun spam détecté</p>
                </div>
            @endif
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-clock text-primary mr-2"></i>
                Activité Récente
            </h2>

            <div class="space-y-4">
                <!-- Recent Posts -->
                <div>
                    <h3 class="font-semibold text-gray-700 mb-2">Derniers Posts</h3>
                    <div class="space-y-2">
                        @forelse($recentPosts->take(5) as $post)
                            <div class="flex items-start gap-3 p-2 hover:bg-gray-50 rounded">
                                <i class="fas fa-file-alt text-primary mt-1"></i>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('forum.posts.show', $post) }}" target="_blank"
                                       class="text-sm font-semibold text-gray-800 hover:text-primary truncate block">
                                        {{ $post->title }}
                                    </a>
                                    <p class="text-xs text-gray-600">
                                        {{ $post->user->name }} • {{ $post->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Aucun post récent</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Comments -->
                <div>
                    <h3 class="font-semibold text-gray-700 mb-2">Derniers Commentaires</h3>
                    <div class="space-y-2">
                        @forelse($recentComments->take(5) as $comment)
                            <div class="flex items-start gap-3 p-2 hover:bg-gray-50 rounded">
                                <i class="fas fa-comment text-success mt-1"></i>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-800 truncate">{{ Str::limit($comment->body, 50) }}</p>
                                    <p class="text-xs text-gray-600">
                                        {{ $comment->user->name }} • {{ $comment->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Aucun commentaire récent</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
