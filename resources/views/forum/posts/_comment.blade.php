<div class="comment {{ $comment->parent_id ? 'ml-12 border-l-2 border-gray-200 pl-6' : '' }}" id="comment-{{ $comment->id }}">
    <div class="bg-gray-50 rounded-lg p-4 {{ $comment->is_best_answer ? 'border-2 border-success' : '' }}">
        <!-- Comment Header -->
        <div class="flex items-start justify-between mb-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-primary to-success rounded-full flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-gray-800">{{ $comment->user->name }}</span>
                        @if($comment->user->badge)
                            @php
                                $badgeInfo = $comment->user->getBadgeInfo();
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 text-xs {{ $badgeInfo['color'] }} {{ $badgeInfo['class'] }} rounded-full">
                                {{ $badgeInfo['icon'] }} {{ $badgeInfo['name'] }}
                            </span>
                        @endif
                        @if($comment->is_best_answer)
                            <span class="inline-flex items-center px-2 py-1 text-xs text-success bg-success/10 rounded-full font-semibold">
                                <i class="fas fa-check-circle mr-1"></i> Meilleure réponse
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600">{{ $comment->created_at->diffForHumans() }}</p>
                </div>
            </div>

            <!-- Best Answer Button -->
            @auth
                @if(!$comment->is_best_answer && $comment->forumPost->user_id === auth()->id() && !$comment->parent_id)
                    <button onclick="markBestAnswer({{ $comment->id }})" 
                            class="text-sm text-success hover:text-success/80 transition font-semibold">
                        <i class="fas fa-check-circle mr-1"></i> Marquer comme meilleure réponse
                    </button>
                @endif
            @endauth
        </div>

        <!-- Comment Body -->
        <div class="mb-4">
            <p class="text-gray-700 whitespace-pre-wrap">{{ $comment->body }}</p>
        </div>

        @if($comment->is_spam)
            <div class="bg-red-50 border-l-4 border-red-500 p-2 mb-3">
                <p class="text-sm text-red-700"><i class="fas fa-exclamation-triangle mr-1"></i> Marqué comme spam</p>
            </div>
        @endif

        <!-- Comment Footer -->
        <div class="flex items-center gap-4 text-sm">
            <!-- Voting -->
            @auth
                <div class="flex items-center gap-2">
                    <button onclick="voteComment({{ $comment->id }}, 1)" 
                            class="text-gray-600 hover:text-success transition">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <span class="font-semibold" id="comment-upvotes-{{ $comment->id }}">{{ $comment->upvotes }}</span>
                    <span class="text-gray-400">|</span>
                    <button onclick="voteComment({{ $comment->id }}, -1)" 
                            class="text-gray-600 hover:text-red-500 transition">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                    <span class="font-semibold" id="comment-downvotes-{{ $comment->id }}">{{ $comment->downvotes }}</span>
                    <span class="ml-2 font-bold text-primary" id="comment-score-{{ $comment->id }}">
                        {{ $comment->score }}
                    </span>
                </div>
            @else
                <div class="flex items-center gap-2 text-gray-600">
                    <span><i class="fas fa-arrow-up text-success"></i> {{ $comment->upvotes }}</span>
                    <span><i class="fas fa-arrow-down text-red-500"></i> {{ $comment->downvotes }}</span>
                    <span class="font-bold">{{ $comment->score }}</span>
                </div>
            @endauth

            <!-- Reply Button -->
            @auth
                @if(!$comment->forumPost->is_locked)
                    <button onclick="replyToComment({{ $comment->id }}, '{{ $comment->user->name }}')" 
                            class="text-primary hover:text-primary/80 transition font-semibold">
                        <i class="fas fa-reply mr-1"></i> Répondre
                    </button>
                @endif
            @endauth

            <!-- Delete Button -->
            @can('delete', $comment)
                <form action="{{ route('forum.comments.destroy', $comment) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Supprimer ce commentaire?')"
                            class="text-red-600 hover:text-red-800 transition font-semibold">
                        <i class="fas fa-trash mr-1"></i> Supprimer
                    </button>
                </form>
            @endcan
        </div>
    </div>

    <!-- Nested Replies -->
    @if($comment->replies && $comment->replies->count() > 0)
        <div class="mt-4 space-y-4">
            @foreach($comment->replies as $reply)
                @include('forum.posts._comment', ['comment' => $reply])
            @endforeach
        </div>
    @endif
</div>
