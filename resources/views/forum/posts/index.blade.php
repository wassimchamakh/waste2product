@extends(auth()->check() ? 'FrontOffice.layout1.app' : 'FrontOffice.layout.app')

@section('title', 'Forum Communautaire - Waste2Product')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-primary to-success rounded-2xl shadow-xl p-8 mb-8 text-white">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold mb-2">💬 Forum Communautaire</h1>
                    <p class="text-white/90 text-lg">Partagez vos idées, posez vos questions, apprenez ensemble</p>
                </div>
                @auth
                    <a href="{{ route('forum.posts.create') }}" 
                       class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-white text-primary rounded-xl hover:bg-gray-100 transition font-semibold shadow-lg">
                        <i class="fas fa-plus mr-2"></i> Nouveau Post
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-white text-primary rounded-xl hover:bg-gray-100 transition font-semibold shadow-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i> Connexion
                    </a>
                @endauth
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-primary/10 rounded-lg mr-4">
                        <i class="fas fa-comments text-primary text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Total Posts</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_posts'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-success/10 rounded-lg mr-4">
                        <i class="fas fa-comment text-success text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Commentaires</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_comments'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-secondary/10 rounded-lg mr-4">
                        <i class="fas fa-users text-secondary text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Membres Actifs</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['active_users'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                
                <!-- Pinned Posts -->
                @if($pinnedPosts && $pinnedPosts->count() > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-thumbtack text-primary mr-2"></i> Posts Épinglés
                    </h2>
                    @foreach($pinnedPosts as $post)
                        @include('forum.posts._post_card', ['post' => $post, 'pinned' => true])
                    @endforeach
                </div>
                @endif

                <!-- Search & Filter -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <form method="GET" action="{{ route('forum.index') }}" class="flex flex-col md:flex-row gap-4">
                        @if(request('mine'))
                            <input type="hidden" name="mine" value="1">
                        @endif
                        <div class="flex-1">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="🔍 Rechercher un post..."
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <select name="sort" 
                                    onchange="this.form.submit()"
                                    class="w-full md:w-auto px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="recent" {{ $sort == 'recent' ? 'selected' : '' }}>📅 Plus récents</option>
                                <option value="popular" {{ $sort == 'popular' ? 'selected' : '' }}>🔥 Plus populaires</option>
                                <option value="most_commented" {{ $sort == 'most_commented' ? 'selected' : '' }}>💬 Plus commentés</option>
                            </select>
                        </div>
                        <button type="submit" 
                                class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition font-semibold">
                            Rechercher
                        </button>
                    </form>
                </div>

                <!-- Posts List -->
                <div class="space-y-6">
                    @forelse($posts as $post)
                        @include('forum.posts._post_card', ['post' => $post])
                    @empty
                        <div class="bg-white rounded-xl shadow-md p-12 text-center">
                            <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun post trouvé</h3>
                            <p class="text-gray-500 mb-6">Soyez le premier à démarrer une discussion!</p>
                            @auth
                                <a href="{{ route('forum.posts.create') }}" 
                                   class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary/90 transition font-semibold">
                                    <i class="fas fa-plus mr-2"></i> Créer un Post
                                </a>
                            @endauth
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($posts->hasPages())
                    <div class="mt-8">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                
                <!-- Quick Actions -->
                <!-- Quick Actions -->
                @auth
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Actions Rapides</h3>
                    <div class="space-y-3">
                        <a href="{{ route('forum.posts.create') }}" 
                           class="block w-full px-4 py-3 bg-primary text-white text-center rounded-lg hover:bg-primary/90 transition font-semibold">
                            <i class="fas fa-plus mr-2"></i> Nouveau Post
                        </a>
                        @if(request('mine'))
                            <a href="{{ route('forum.index') }}" 
                               class="block w-full px-4 py-3 bg-gray-100 text-gray-700 text-center rounded-lg hover:bg-gray-200 transition font-semibold">
                                <i class="fas fa-list mr-2"></i> Tous les Posts
                            </a>
                        @else
                            <a href="{{ route('forum.index', ['mine' => 1]) }}" 
                               class="block w-full px-4 py-3 bg-gray-100 text-gray-700 text-center rounded-lg hover:bg-gray-200 transition font-semibold">
                                <i class="fas fa-user mr-2"></i> Mes Posts
                            </a>
                        @endif
                    </div>
                </div>

                <!-- User Stats -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Votre Profil</h3>
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary to-success rounded-full flex items-center justify-center text-white text-2xl font-bold mr-4">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                            <div class="flex items-center mt-1">
                                @php
                                    $badgeInfo = auth()->user()->getBadgeInfo();
                                @endphp
                                <span class="text-sm {{ $badgeInfo['color'] }} font-semibold">
                                    {{ $badgeInfo['icon'] }} {{ $badgeInfo['name'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">🏆 Réputation</span>
                            <span class="font-semibold text-primary">{{ auth()->user()->reputation }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">📝 Posts</span>
                            <span class="font-semibold">{{ auth()->user()->posts_count }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">💬 Commentaires</span>
                            <span class="font-semibold">{{ auth()->user()->comments_count }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">✅ Meilleures réponses</span>
                            <span class="font-semibold">{{ auth()->user()->best_answers_count }}</span>
                        </div>
                    </div>
                </div>
                @endauth

                <!-- Forum Rules -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">📜 Règles du Forum</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-success mt-1 mr-2"></i>
                            <span>Soyez respectueux envers tous les membres</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-success mt-1 mr-2"></i>
                            <span>Pas de spam ou contenu inapproprié</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-success mt-1 mr-2"></i>
                            <span>Partagez des informations utiles</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-success mt-1 mr-2"></i>
                            <span>Marquez les meilleures réponses</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
