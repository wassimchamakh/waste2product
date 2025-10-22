@extends(auth()->check() ? 'FrontOffice.layout1.app' : 'FrontOffice.layout.app')

@section('title', $post->title . ' - Forum Waste2Product')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('forum.index') }}" 
               class="inline-flex items-center text-primary hover:text-primary/80 transition font-semibold">
                <i class="fas fa-arrow-left mr-2"></i> Retour au forum
            </a>
        </div>

        <!-- Post Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <!-- Post Header -->
            <div class="bg-gradient-to-r from-primary to-success p-6 text-white">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold mb-3">{{ $post->title }}</h1>
                        <div class="flex items-center gap-4 text-white/90">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold">{{ $post->user->name }}</p>
                                    @if($post->user->badge)
                                        @php
                                            $badgeInfo = $post->user->getBadgeInfo();
                                        @endphp
                                        <span class="text-xs">{{ $badgeInfo['icon'] }} {{ $badgeInfo['name'] }}</span>
                                    @endif
                                </div>
                            </div>
                            <span>•</span>
                            <span>{{ $post->created_at->format('d M Y à H:i') }}</span>
                            <span>•</span>
                            <span><i class="fas fa-eye mr-1"></i> {{ $post->views_count }} vues</span>
                        </div>
                    </div>
                    @if($post->is_pinned || $post->is_locked)
                        <div class="flex gap-2">
                            @if($post->is_pinned)
                                <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-semibold">
                                    <i class="fas fa-thumbtack mr-1"></i> Épinglé
                                </span>
                            @endif
                            @if($post->is_locked)
                                <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-semibold">
                                    <i class="fas fa-lock mr-1"></i> Verrouillé
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Post Body -->
            <div class="p-8">
                <div class="prose max-w-none mb-8">
                    <p class="text-gray-700 text-lg leading-relaxed whitespace-pre-wrap">{{ $post->body }}</p>
                </div>

                <!-- AI Summary Section -->
                @auth
                <div class="mb-6">
                    <button type="button"
                            onclick="event.preventDefault(); generateAISummary({{ $post->id }}); return false;" 
                            id="ai-summary-btn"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition font-semibold shadow-lg hover:shadow-xl">
                        <i class="fas fa-magic mr-2"></i>
                        ✨ Résumé IA
                    </button>
                    
                    <!-- Loading State -->
                    <div id="ai-summary-loading" class="hidden mt-4">
                        <div class="flex items-center gap-3 text-purple-600">
                            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-purple-600"></div>
                            <span>L'IA génère le résumé...</span>
                        </div>
                    </div>
                    
                    <!-- Summary Display -->
                    <div id="ai-summary-result" class="hidden mt-4 bg-gradient-to-r from-purple-50 to-pink-50 border-l-4 border-purple-500 rounded-lg p-6 shadow-md">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-robot text-purple-500 text-2xl mt-1"></i>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                                    <span>Résumé généré par l'IA</span>
                                    <span class="text-xs bg-purple-500 text-white px-2 py-1 rounded-full">GEMINI</span>
                                </h3>
                                <p id="ai-summary-text" class="text-gray-700 leading-relaxed"></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Error State -->
                    <div id="ai-summary-error" class="hidden mt-4 bg-red-50 border-l-4 border-red-500 rounded-lg p-4">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                            <span id="ai-summary-error-text" class="text-red-700"></span>
                        </div>
                    </div>
                </div>
                @endauth

                <!-- Spam Warning -->
                @if($post->is_spam)
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3"></i>
                            <p class="text-red-700 font-semibold">Ce post a été marqué comme spam et est en cours de modération.</p>
                        </div>
                    </div>
                @endif

                <!-- Post Footer -->
                <div class="border-t pt-6">
                    <div class="flex items-center justify-between">
                        <!-- Voting -->
                        <div class="flex items-center gap-4">
                            @auth
                                <div class="flex items-center gap-2 bg-gray-50 rounded-lg p-2">
                                    <button onclick="votePost({{ $post->id }}, 1)" 
                                            id="upvote-btn-{{ $post->id }}"
                                            class="px-4 py-2 rounded-lg hover:bg-success/10 transition {{ $userVote == 1 ? 'bg-success text-white' : 'text-gray-600' }}">
                                        <i class="fas fa-arrow-up"></i>
                                        <span class="ml-1 font-bold" id="upvotes-{{ $post->id }}">{{ $post->upvotes }}</span>
                                    </button>
                                    <span class="text-gray-400">|</span>
                                    <button onclick="votePost({{ $post->id }}, -1)" 
                                            id="downvote-btn-{{ $post->id }}"
                                            class="px-4 py-2 rounded-lg hover:bg-red-50 transition {{ $userVote == -1 ? 'bg-red-500 text-white' : 'text-gray-600' }}">
                                        <i class="fas fa-arrow-down"></i>
                                        <span class="ml-1 font-bold" id="downvotes-{{ $post->id }}">{{ $post->downvotes }}</span>
                                    </button>
                                    <span class="ml-2 text-lg font-bold" id="score-{{ $post->id }}">
                                        Score: {{ $post->score }}
                                    </span>
                                </div>
                            @else
                                <div class="flex items-center gap-4 text-gray-600">
                                    <span><i class="fas fa-arrow-up text-success mr-1"></i> {{ $post->upvotes }}</span>
                                    <span><i class="fas fa-arrow-down text-red-500 mr-1"></i> {{ $post->downvotes }}</span>
                                    <span class="font-bold">Score: {{ $post->score }}</span>
                                </div>
                            @endauth
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-3">
                            @can('update', $post)
                                <a href="{{ route('forum.posts.edit', $post) }}" 
                                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                    <i class="fas fa-edit mr-1"></i> Modifier
                                </a>
                            @endcan
                            @can('delete', $post)
                                <form action="{{ route('forum.posts.destroy', $post) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce post?')"
                                            class="px-4 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                                        <i class="fas fa-trash mr-1"></i> Supprimer
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="bg-white rounded-2xl shadow-xl p-8" id="comments">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-comments text-primary mr-2"></i>
                Commentaires ({{ $post->all_comments_count ?? 0 }})
            </h2>

            <!-- Add Comment Form -->
            @auth
                @if(!$post->is_locked)
                    <div class="bg-gray-50 rounded-xl p-6 mb-8">
                        <!-- AI Protection Badge -->
                        <div class="mb-4 flex items-center gap-2 text-sm">
                            <div class="flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 rounded-full">
                                <i class="fas fa-shield-alt"></i>
                                <span class="font-semibold">Protection IA active</span>
                            </div>
                            <span class="text-gray-500">• Détection automatique de spam et contenu inapproprié</span>
                        </div>

                        <form id="comment-form" onsubmit="postComment(event)">
                            @csrf
                            <input type="hidden" name="forum_post_id" value="{{ $post->id }}">
                            <input type="hidden" name="parent_id" id="parent_id" value="">
                            
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Votre commentaire</label>
                                <textarea name="body" 
                                          id="comment-body"
                                          rows="4" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                          placeholder="Partagez votre avis..." 
                                          required></textarea>
                                <div id="reply-indicator" class="hidden mt-2 p-2 bg-blue-50 rounded-lg">
                                    <span class="text-sm text-blue-700">Réponse à <strong id="reply-to-name"></strong></span>
                                    <button type="button" onclick="cancelReply()" class="ml-2 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Votre commentaire sera vérifié automatiquement par l'IA
                                </p>
                                <button type="submit" 
                                        class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition font-semibold">
                                    <i class="fas fa-paper-plane mr-2"></i> Publier
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-8">
                        <p class="text-yellow-700"><i class="fas fa-lock mr-2"></i> Ce post est verrouillé. Vous ne pouvez plus commenter.</p>
                    </div>
                @endif
            @else
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8">
                    <p class="text-blue-700">
                        <a href="{{ route('login') }}" class="font-semibold underline">Connectez-vous</a> pour participer à la discussion.
                    </p>
                </div>
            @endauth

            <!-- Comments List -->
            <div id="comments-list" class="space-y-6">
                @forelse($post->comments as $comment)
                    @include('forum.posts._comment', ['comment' => $comment])
                @empty
                    <p class="text-center text-gray-500 py-8">Aucun commentaire pour l'instant. Soyez le premier à commenter!</p>
                @endforelse
            </div>
        </div>

        <!-- Related Posts -->
        @if($relatedPosts && $relatedPosts->count() > 0)
        <div class="mt-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Plus de posts par {{ $post->user->name }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($relatedPosts as $related)
                    <a href="{{ route('forum.posts.show', $related) }}" 
                       class="block bg-white rounded-lg shadow-md p-4 hover:shadow-xl transition">
                        <h4 class="font-semibold text-gray-800 mb-2">{{ $related->title }}</h4>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $related->excerpt }}</p>
                        <div class="flex items-center gap-4 mt-3 text-xs text-gray-500">
                            <span><i class="fas fa-arrow-up text-success"></i> {{ $related->upvotes }}</span>
                            <span><i class="fas fa-comments"></i> {{ $related->all_comments_count ?? 0 }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Vote on post
async function votePost(postId, vote) {
    try {
        const response = await fetch(`/forum/posts/${postId}/vote`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ vote })
        });

        const data = await response.json();
        
        if (data.success) {
            document.getElementById(`upvotes-${postId}`).textContent = data.upvotes;
            document.getElementById(`downvotes-${postId}`).textContent = data.downvotes;
            document.getElementById(`score-${postId}`).textContent = `Score: ${data.score}`;
            
            // Update button states
            const upBtn = document.getElementById(`upvote-btn-${postId}`);
            const downBtn = document.getElementById(`downvote-btn-${postId}`);
            
            upBtn.classList.remove('bg-success', 'text-white');
            downBtn.classList.remove('bg-red-500', 'text-white');
            
            if (data.userVote == 1) {
                upBtn.classList.add('bg-success', 'text-white');
            } else if (data.userVote == -1) {
                downBtn.classList.add('bg-red-500', 'text-white');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Erreur lors du vote');
    }
}

// Post comment
async function postComment(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Envoi en cours...';
    
    try {
        const response = await fetch('{{ route("forum.comments.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();
        
        if (data.success) {
            // Show AI verification message if present
            if (data.ai_warning) {
                showNotification(data.ai_warning, 'success');
            }
            
            // Reset form
            form.reset();
            document.getElementById('parent_id').value = '';
            document.getElementById('reply-indicator').classList.add('hidden');
            
            // Reload page to show new comment
            setTimeout(() => location.reload(), 1000);
        } else {
            // Handle toxic content detection
            if (data.is_toxic) {
                showNotification('❌ ' + data.message + '\n' + (data.reason || ''), 'error');
            } else {
                showNotification(data.message || 'Erreur lors de l\'ajout du commentaire', 'error');
            }
            
            // Re-enable button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Publier';
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Erreur de connexion. Veuillez réessayer.', 'error');
        
        // Re-enable button
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Publier';
    }
}

// Show notification helper
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-md ${
        type === 'error' ? 'bg-red-500 text-white' : 
        type === 'success' ? 'bg-green-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-start gap-3">
            <i class="fas fa-${type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'} text-xl"></i>
            <div class="flex-1">
                <p class="font-semibold whitespace-pre-line">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transition = 'opacity 0.5s';
        setTimeout(() => notification.remove(), 500);
    }, 5000);
}

// Reply to comment
function replyToComment(commentId, userName) {
    document.getElementById('parent_id').value = commentId;
    document.getElementById('reply-to-name').textContent = userName;
    document.getElementById('reply-indicator').classList.remove('hidden');
    document.getElementById('comment-body').focus();
}

// Cancel reply
function cancelReply() {
    document.getElementById('parent_id').value = '';
    document.getElementById('reply-indicator').classList.add('hidden');
}

// Vote on comment
async function voteComment(commentId, vote) {
    try {
        const response = await fetch(`/forum/comments/${commentId}/vote`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ vote })
        });

        const data = await response.json();
        
        if (data.success) {
            document.getElementById(`comment-upvotes-${commentId}`).textContent = data.upvotes;
            document.getElementById(`comment-downvotes-${commentId}`).textContent = data.downvotes;
            document.getElementById(`comment-score-${commentId}`).textContent = data.score;
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Mark as best answer
async function markBestAnswer(commentId) {
    if (!confirm('Marquer ce commentaire comme meilleure réponse?')) return;
    
    try {
        const response = await fetch(`/forum/comments/${commentId}/mark-best-answer`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        
        if (data.success) {
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Generate AI Summary
async function generateAISummary(postId) {
    const btn = document.getElementById('ai-summary-btn');
    const loading = document.getElementById('ai-summary-loading');
    const result = document.getElementById('ai-summary-result');
    const error = document.getElementById('ai-summary-error');
    const summaryText = document.getElementById('ai-summary-text');
    const errorText = document.getElementById('ai-summary-error-text');
    
    // Hide previous results
    result.classList.add('hidden');
    error.classList.add('hidden');
    
    // Show loading
    btn.disabled = true;
    btn.classList.add('opacity-50', 'cursor-not-allowed');
    loading.classList.remove('hidden');
    
    try {
        const response = await fetch(`/forum/posts/${postId}/summarize`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        
        if (data.success) {
            summaryText.textContent = data.summary;
            result.classList.remove('hidden');
        } else {
            errorText.textContent = data.message || 'Erreur lors de la génération du résumé';
            error.classList.remove('hidden');
        }
    } catch (err) {
        console.error('Error:', err);
        errorText.textContent = 'Erreur de connexion. Veuillez réessayer.';
        error.classList.remove('hidden');
    } finally {
        loading.classList.add('hidden');
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}
</script>
@endpush
@endsection
