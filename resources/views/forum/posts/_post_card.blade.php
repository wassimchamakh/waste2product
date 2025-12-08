<div class="bg-white rounded-lg sm:rounded-xl shadow-md hover:shadow-xl transition-shadow p-4 sm:p-6 {{ isset($pinned) && $pinned ? 'border-2 border-primary' : '' }}">
    <div class="flex flex-col sm:flex-row items-start gap-3 sm:gap-4">
        <!-- Author Avatar -->
        <div class="flex-shrink-0 hidden sm:block">
            <div class="w-12 h-12 bg-gradient-to-br from-primary to-success rounded-full flex items-center justify-center text-white text-lg font-bold">
                {{ strtoupper(substr($post->user->name, 0, 1)) }}
            </div>
        </div>

        <!-- Post Content -->
        <div class="flex-1 min-w-0 w-full">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-start justify-between mb-2 sm:mb-3 gap-2">
                <div class="flex-1 min-w-0">
                    <div class="flex items-start gap-2 mb-1">
                        <!-- Mobile Avatar -->
                        <div class="flex-shrink-0 sm:hidden">
                            <div class="w-8 h-8 bg-gradient-to-br from-primary to-success rounded-full flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('forum.posts.show', $post) }}" 
                               class="text-base sm:text-lg font-bold text-gray-800 hover:text-primary transition line-clamp-2 sm:line-clamp-1">
                                {{ $post->title }}
                            </a>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-xs sm:text-sm text-gray-600">
                        <span class="font-semibold">{{ $post->user->name }}</span>
                        @if($post->is_pinned)
                            <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 sm:py-1 text-xs font-semibold text-primary bg-primary/10 rounded-full whitespace-nowrap">
                                <i class="fas fa-thumbtack mr-1"></i> <span class="hidden sm:inline">Épinglé</span>
                            </span>
                        @endif
                        @if($post->is_locked)
                            <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 sm:py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded-full whitespace-nowrap">
                                <i class="fas fa-lock mr-1"></i> <span class="hidden sm:inline">Verrouillé</span>
                            </span>
                        @endif
                        @if($post->is_spam)
                            <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 sm:py-1 text-xs font-semibold text-red-600 bg-red-100 rounded-full whitespace-nowrap">
                                <i class="fas fa-exclamation-triangle mr-1"></i> <span class="hidden sm:inline">Spam</span>
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <span class="font-semibold">{{ $post->user->name }}</span>
                        @if($post->user->badge)
                            @php
                                $badgeInfo = $post->user->getBadgeInfo();
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 text-xs {{ $badgeInfo['color'] }} {{ $badgeInfo['class'] }} rounded-full">
                                {{ $badgeInfo['icon'] }} {{ $badgeInfo['name'] }}
                            </span>
                        @endif
                        <span>•</span>
                        <span>{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Excerpt -->
            <p class="text-sm sm:text-base text-gray-700 mb-3 sm:mb-4 line-clamp-2">
                {{ $post->excerpt }}
            </p>

            <!-- Footer Stats -->
            <div class="flex flex-wrap items-center gap-3 sm:gap-6 text-xs sm:text-sm text-gray-600">
                <!-- Votes -->
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1">
                        <i class="fas fa-arrow-up text-success"></i>
                        <span class="font-semibold">{{ $post->upvotes }}</span>
                    </div>
                    <span class="text-gray-400">|</span>
                    <div class="flex items-center gap-1">
                        <i class="fas fa-arrow-down text-red-500"></i>
                        <span class="font-semibold">{{ $post->downvotes }}</span>
                    </div>
                </div>

                <!-- Comments -->
                <a href="{{ route('forum.posts.show', $post) }}#comments" 
                   class="flex items-center gap-1 hover:text-primary transition">
                    <i class="fas fa-comments"></i>
                    <span class="font-semibold">{{ $post->all_comments_count ?? 0 }}</span>
                    <span>commentaires</span>
                </a>

                <!-- Views -->
                <div class="flex items-center gap-1">
                    <i class="fas fa-eye"></i>
                    <span class="font-semibold">{{ $post->views_count }}</span>
                    <span>vues</span>
                </div>

                <!-- Status Badge -->
                <span class="ml-auto">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $post->getStatusBadgeClass() }}">
                        {{ ucfirst($post->status) }}
                    </span>
                </span>
            </div>
        </div>
    </div>
</div>
