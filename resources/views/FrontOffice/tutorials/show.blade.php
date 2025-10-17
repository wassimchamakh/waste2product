@extends('FrontOffice.layout1.app')

@section('title', $tutorial->title . ' - Waste2Product')

@push('styles')
<style>
    .hero-image-overlay {
        background: linear-gradient(45deg, rgba(46, 125, 71, 0.8), rgba(6, 214, 160, 0.6));
    }
    
    .difficulty-beginner { 
        @apply bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200; 
    }
    .difficulty-intermediate { 
        @apply bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200; 
    }
    .difficulty-advanced { 
        @apply bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200; 
    }
    .difficulty-expert { 
        @apply bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200; 
    }
    
    .category-recycling { 
        @apply bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200; 
    }
    .category-composting { 
        @apply bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200; 
    }
    .category-energy { 
        @apply bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200; 
    }
    .category-water { 
        @apply bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200; 
    }
    .category-waste-reduction { 
        @apply bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200; 
    }
    .category-gardening { 
        @apply bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200; 
    }
    .category-diy { 
        @apply bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200; 
    }
    .category-general { 
        @apply bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200; 
    }

    .step-item {
        transition: all 0.3s ease;
    }
    .step-item:hover {
        background-color: #f9fafb;
        transform: translateX(4px);
    }
    .step-item.completed {
        background-color: #f0f9ff;
        border-left: 4px solid #2E7D47;
    }

    .comment-thread {
        border-left: 2px solid #e5e7eb;
    }
    .comment-reply {
        background-color: #f9fafb;
        border-left: 3px solid #06D6A0;
    }

    .modal {
        transition: all 0.3s ease-in-out;
    }
    .modal.show {
        opacity: 1;
        pointer-events: auto;
    }

    .sticky-sidebar {
        position: sticky;
        top: 2rem;
        max-height: calc(100vh - 4rem);
        overflow-y: auto;
    }

    .rating-stars {
        display: flex;
        gap: 0.25rem;
    }
    .rating-star {
        cursor: pointer;
        transition: color 0.2s ease;
    }
    .rating-star:hover,
    .rating-star.active {
        color: #fbbf24;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="relative h-96 md:h-[500px] overflow-hidden">
    <img src="{{ $tutorial->thumbnail_url ?? 'https://picsum.photos/1200/500?random=' . $tutorial->id }}" 
         alt="{{ $tutorial->title }}" 
         class="w-full h-full object-cover">
    <div class="absolute inset-0 hero-image-overlay"></div>
    
    <!-- Tutorial Info Overlay -->
    <div class="absolute inset-0 flex items-end">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pb-8">
            <div class="flex flex-wrap gap-3 mb-4">
                <!-- Difficulty Badge -->
                <span class="px-3 py-1 text-sm font-medium rounded-full difficulty-{{ strtolower($tutorial->difficulty_level) }}">
                    {{ $tutorial->difficulty_level }}
                </span>
                
                <!-- Category Badge -->
                <span class="px-3 py-1 text-sm font-medium rounded-full category-{{ $tutorial->category_slug }}">
                    {{ $tutorial->category }}
                </span>

                <!-- Featured Badge -->
                @if($tutorial->is_featured)
                    <span class="bg-warning text-white px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-star mr-1"></i>Featured
                    </span>
                @endif
            </div>
            
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">
                {{ $tutorial->title }}
            </h1>
            
            <!-- Creator Info -->
            <div class="flex items-center text-white mb-6">
                <img src="{{ $tutorial->creator->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($tutorial->creator->name ?? 'Unknown') }}" 
                     alt="{{ $tutorial->creator->name ?? 'Unknown' }}" 
                     class="w-10 h-10 rounded-full mr-3 border-2 border-white/20">
                <div>
                    <div class="font-medium">{{ $tutorial->creator->name ?? 'Unknown Author' }}</div>
                    <div class="text-sm opacity-90">
                        Published {{ $tutorial->published_at ? $tutorial->published_at->diffForHumans() : $tutorial->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                @php
                    $buttonText = 'Commencer l\'apprentissage';
                    $buttonIcon = 'fa-play';
                    $startStep = 1;
                    
                    if ($userProgress) {
                        if ($userProgress->is_completed) {
                            $buttonText = 'Revoir le tutoriel';
                            $buttonIcon = 'fa-redo';
                        } else {
                            $buttonText = 'Continuer l\'apprentissage';
                            $buttonIcon = 'fa-play-circle';
                            $startStep = $userProgress->current_step ?? 1;
                        }
                    }
                @endphp
                
                <a href="{{ route('tutorials.step', [$tutorial->slug, $startStep]) }}" 
                   class="bg-secondary text-white px-6 py-3 rounded-lg hover:bg-teal-400 transition-colors font-semibold flex items-center">
                    <i class="fas {{ $buttonIcon }} mr-2"></i>{{ $buttonText }}
                </a>
                
                @auth
                    <!-- Creator Actions: Edit and Delete -->
                    @if($tutorial->created_by == Auth::id())
                        <a href="{{ route('tutorials.edit', $tutorial->slug) }}" 
                           class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors font-semibold flex items-center">
                            <i class="fas fa-edit mr-2"></i>Modifier
                        </a>
                        
                        <button onclick="confirmDeleteTutorial('{{ $tutorial->slug }}', '{{ $tutorial->title }}')" 
                                class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition-colors font-semibold flex items-center">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    @endif
                    
                    <button onclick="toggleBookmark({{ $tutorial->id }})" 
                            id="bookmark-btn"
                            class="bg-white/20 backdrop-blur-sm text-white px-4 py-3 rounded-lg hover:bg-white/30 transition-colors">
                        <i class="far fa-heart mr-2"></i>Enregistrer
                    </button>
                @endauth

                <button onclick="showShareModal()" 
                        class="bg-white/20 backdrop-blur-sm text-white px-4 py-3 rounded-lg hover:bg-white/30 transition-colors">
                    <i class="fas fa-share mr-2"></i>Partager
                </button>
            </div>
            
            <!-- Progress Bar (if in progress) -->
            @if($userProgress && !$userProgress->is_completed)
                <div class="mt-4 bg-white/20 backdrop-blur-sm rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-white text-sm font-medium">Votre progression</span>
                        <span class="text-white text-sm font-bold">{{ $userProgress->progress_percentage }}%</span>
                    </div>
                    <div class="w-full bg-white/30 rounded-full h-3 overflow-hidden">
                        <div class="bg-secondary h-full transition-all duration-500 rounded-full" 
                             style="width: {{ $userProgress->progress_percentage }}%"></div>
                    </div>
                    <p class="text-white/80 text-xs mt-2">
                        {{ $userProgress->total_steps_completed }} sur {{ $tutorial->steps->count() }} étapes terminées
                    </p>
                </div>
            @endif
            
            <!-- Completed Badge -->
            @if($userProgress && $userProgress->is_completed)
                <div class="mt-4 bg-green-500/20 backdrop-blur-sm rounded-lg p-4 border-2 border-green-400">
                    <div class="flex items-center gap-2 text-green-100">
                        <i class="fas fa-check-circle text-2xl"></i>
                        <div>
                            <p class="font-bold">Tutoriel terminé!</p>
                            <p class="text-sm">Complété le {{ $userProgress->completed_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Back Button -->
    <div class="absolute top-4 left-4">
        <a href="{{ route('tutorials.index') }}" 
           class="bg-white/20 backdrop-blur-sm text-white p-3 rounded-full hover:bg-white/30 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Meta Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-primary">{{ $tutorial->duration }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Duration</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-primary">{{ number_format($tutorial->views_count) }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Views</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-primary">{{ number_format($tutorial->completion_count) }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Completed</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-primary">{{ $tutorial->steps_count }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Steps</div>
                    </div>
                </div>

                <!-- Rating -->
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-center">
                        <div class="flex text-yellow-400 mr-3">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= floor($tutorial->average_rating) ? '' : 'text-gray-300 dark:text-gray-600' }}"></i>
                            @endfor
                        </div>
                        <span class="text-lg font-medium">{{ number_format($tutorial->average_rating, 1) }}</span>
                        <span class="text-gray-600 dark:text-gray-400 ml-2">({{ $tutorial->total_ratings }} ratings)</span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">About This Tutorial</h2>
                <div class="prose max-w-none dark:prose-invert">
                    {!! nl2br(e($tutorial->description)) !!}
                </div>
                
                @if($tutorial->content)
                    <div class="mt-6 prose max-w-none dark:prose-invert">
                        {!! $tutorial->content !!}
                    </div>
                @endif
            </div>

            <!-- Prerequisites -->
            @if($tutorial->prerequisites)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-exclamation-circle text-warning mr-2"></i>Prerequisites
                    </h2>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <div class="prose max-w-none dark:prose-invert">
                            {!! nl2br(e($tutorial->prerequisites)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Learning Outcomes -->
            @if($tutorial->learning_outcomes)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-bullseye text-success mr-2"></i>What You'll Learn
                    </h2>
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        @php
                            $outcomes = explode("\n", $tutorial->learning_outcomes);
                        @endphp
                        <ul class="space-y-2">
                            @foreach($outcomes as $outcome)
                                @if(trim($outcome))
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-success mr-2 mt-1"></i>
                                        <span>{{ trim($outcome) }}</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Tags -->
            @if($tutorial->tags)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Tags</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tutorial->tags_array as $tag)
                            <span class="px-3 py-1 bg-primary/10 text-primary border border-primary/20 rounded-full text-sm">
                                #{{ trim($tag) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Steps Overview -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tutorial Steps</h2>
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $tutorial->steps_count }} steps • {{ $tutorial->duration }}
                    </span>
                </div>

                <div class="space-y-3">
                    @foreach($tutorial->steps as $step)
                        @php
                            $isCompleted = in_array($step->id, $completedSteps);
                            $isCurrent = $userProgress && $userProgress->current_step == $step->step_number;
                        @endphp
                        <div class="step-item border {{ $isCompleted ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-gray-200 dark:border-gray-700' }} {{ $isCurrent ? 'ring-2 ring-secondary' : '' }} rounded-lg p-4 cursor-pointer hover:shadow-md transition-all"
                             onclick="window.location.href='{{ route('tutorials.step', [$tutorial->slug, $step->step_number]) }}'">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        @if($isCompleted)
                                            <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center">
                                                <i class="fas fa-check text-sm"></i>
                                            </div>
                                        @elseif($isCurrent)
                                            <div class="w-8 h-8 bg-secondary text-white rounded-full flex items-center justify-center text-sm font-medium">
                                                {{ $step->step_number }}
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium">
                                                {{ $step->step_number }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                            {{ $step->title }}
                                        </h3>
                                        @if($step->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                {{ Str::limit($step->description, 100) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                    @if($step->estimated_time > 0)
                                        <span class="flex items-center">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $step->estimated_time }}min
                                        </span>
                                    @endif
                                    @if($step->video_url)
                                        <i class="fas fa-play-circle text-secondary"></i>
                                    @endif
                                    @if($step->image_url)
                                        <i class="fas fa-image text-warning"></i>
                                    @endif
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('tutorials.step', [$tutorial->slug, 1]) }}" 
                       class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-green-600 transition-colors font-medium">
                        <i class="fas fa-play mr-2"></i>Start Tutorial
                    </a>
                </div>
            </div>

            <!-- Comments Section -->
            <div id="comments-section" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                    Comments ({{ $tutorial->comments_count }})
                </h2>

                <!-- Add Comment Form -->
                @auth
                    <form id="comment-form" class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        @csrf
                        <input type="hidden" name="tutorial_id" value="{{ $tutorial->id }}">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Rate this tutorial
                            </label>
                            <div class="rating-stars" id="rating-input">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="rating-star fas fa-star text-gray-300 text-xl" data-rating="{{ $i }}"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating-value">
                        </div>
                        <div class="mb-4">
                            <textarea name="comment_text" 
                                      rows="4" 
                                      placeholder="Share your thoughts about this tutorial..."
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-600 text-gray-900 dark:text-white"
                                      required></textarea>
                        </div>
                        <button type="submit" 
                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-green-600 transition-colors font-medium">
                            <i class="fas fa-comment mr-2"></i>Post Comment
                        </button>
                    </form>
                @else
                    <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg text-center">
                        <p class="text-gray-600 dark:text-gray-400 mb-3">Please log in to leave a comment</p>
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-green-600 transition-colors">
                            <i class="fas fa-sign-in-alt mr-2"></i>Log In
                        </a>
                    </div>
                @endauth

                <!-- Comments List -->
                <div id="comments-list" class="space-y-6">
                    @forelse($tutorial->comments()->with('user')->where('parent_comment_id', null)->orderBy('created_at', 'desc')->paginate(10) as $comment)
                        <div class="comment-thread">
                            <div class="flex space-x-4">
                                <img src="{{ $comment->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}" 
                                     alt="{{ $comment->user->name }}" 
                                     class="w-10 h-10 rounded-full flex-shrink-0">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <h4 class="font-medium text-gray-900 dark:text-white">
                                            {{ $comment->user->name }}
                                        </h4>
                                        @if($comment->rating)
                                            <div class="flex text-yellow-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star text-xs {{ $i <= $comment->rating ? '' : 'text-gray-300 dark:text-gray-600' }}"></i>
                                                @endfor
                                            </div>
                                        @endif
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $comment->created_at->diffForHumans() }}
                                        </span>
                                        @if($comment->is_edited)
                                            <span class="text-xs text-gray-400">(edited)</span>
                                        @endif
                                    </div>
                                    <p class="text-gray-700 dark:text-gray-300 mb-3">
                                        {{ $comment->comment_text }}
                                    </p>
                                    <div class="flex items-center space-x-4 text-sm">
                                        <button onclick="toggleHelpful({{ $comment->id }})" 
                                                class="flex items-center text-gray-500 hover:text-primary transition-colors">
                                            <i class="fas fa-thumbs-up mr-1"></i>
                                            Helpful ({{ $comment->helpful_count }})
                                        </button>
                                        @auth
                                            <button onclick="replyToComment({{ $comment->id }})" 
                                                    class="text-gray-500 hover:text-primary transition-colors">
                                                <i class="fas fa-reply mr-1"></i>Reply
                                            </button>
                                            @if(auth()->id() === $comment->user_id)
                                                <button onclick="editComment({{ $comment->id }})" 
                                                        class="text-gray-500 hover:text-primary transition-colors">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </button>
                                                <button onclick="deleteComment({{ $comment->id }})" 
                                                        class="text-gray-500 hover:text-accent transition-colors">
                                                    <i class="fas fa-trash mr-1"></i>Delete
                                                </button>
                                            @endif
                                        @endauth
                                    </div>

                                    <!-- Replies -->
                                    @if($comment->replies->count() > 0)
                                        <div class="mt-4 space-y-4">
                                            @foreach($comment->replies as $reply)
                                                <div class="comment-reply p-4 rounded-lg ml-6">
                                                    <div class="flex space-x-3">
                                                        <img src="{{ $reply->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($reply->user->name) }}" 
                                                             alt="{{ $reply->user->name }}" 
                                                             class="w-8 h-8 rounded-full flex-shrink-0">
                                                        <div class="flex-1">
                                                            <div class="flex items-center space-x-2 mb-1">
                                                                <h5 class="font-medium text-gray-900 dark:text-white text-sm">
                                                                    {{ $reply->user->name }}
                                                                </h5>
                                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                                    {{ $reply->created_at->diffForHumans() }}
                                                                </span>
                                                            </div>
                                                            <p class="text-gray-700 dark:text-gray-300 text-sm">
                                                                {{ $reply->comment_text }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-comments text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No comments yet</h3>
                            <p class="text-gray-600 dark:text-gray-400">Be the first to share your thoughts!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Comments Pagination -->
                @if($tutorial->comments()->where('parent_comment_id', null)->count() > 10)
                    <div class="mt-6">
                        {{ $tutorial->comments()->where('parent_comment_id', null)->paginate(10)->fragment('comments-section')->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky-sidebar space-y-6">
                <!-- More from Creator -->
                @if($moreFromCreator->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                            More from {{ $tutorial->creator->name }}
                        </h3>
                        <div class="space-y-4">
                            @foreach($moreFromCreator as $relatedTutorial)
                                <a href="{{ route('tutorials.show', $relatedTutorial->slug) }}" 
                                   class="block hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg p-3 transition-colors">
                                    <div class="flex space-x-3">
                                        <img src="{{ $relatedTutorial->thumbnail_url ?? 'https://picsum.photos/80/60?random=' . $relatedTutorial->id }}" 
                                             alt="{{ $relatedTutorial->title }}" 
                                             class="w-20 h-15 object-cover rounded flex-shrink-0">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-medium text-gray-900 dark:text-white text-sm line-clamp-2">
                                                {{ $relatedTutorial->title }}
                                            </h4>
                                            <div class="flex items-center mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                <span>{{ $relatedTutorial->duration }}</span>
                                                <span class="mx-1">•</span>
                                                <span>{{ number_format($relatedTutorial->views_count) }} views</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Related Tutorials -->
                @if($relatedTutorials->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                            Related {{ $tutorial->category }} Tutorials
                        </h3>
                        <div class="space-y-4">
                            @foreach($relatedTutorials as $relatedTutorial)
                                <a href="{{ route('tutorials.show', $relatedTutorial->slug) }}" 
                                   class="block hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg p-3 transition-colors">
                                    <div class="flex space-x-3">
                                        <img src="{{ $relatedTutorial->thumbnail_url ?? 'https://picsum.photos/80/60?random=' . $relatedTutorial->id }}" 
                                             alt="{{ $relatedTutorial->title }}" 
                                             class="w-20 h-15 object-cover rounded flex-shrink-0">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-medium text-gray-900 dark:text-white text-sm line-clamp-2">
                                                {{ $relatedTutorial->title }}
                                            </h4>
                                            <div class="flex items-center mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                <span>{{ $relatedTutorial->duration }}</span>
                                                <span class="mx-1">•</span>
                                                <div class="flex text-yellow-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star text-xs {{ $i <= floor($relatedTutorial->average_rating) ? '' : 'text-gray-300' }}"></i>
                                                    @endfor
                                                </div>
                                                <span class="ml-1">{{ number_format($relatedTutorial->average_rating, 1) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="#comments-section" 
                           class="block w-full text-center px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <i class="fas fa-comments mr-2"></i>View Comments
                        </a>
                        <button onclick="showShareModal()" 
                                class="block w-full text-center px-4 py-2 border border-secondary text-secondary rounded-lg hover:bg-secondary hover:text-white transition-colors">
                            <i class="fas fa-share mr-2"></i>Share Tutorial
                        </button>
                        <button onclick="showReportModal()" 
                                class="block w-full text-center px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-flag mr-2"></i>Report Issue
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div id="shareModal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 opacity-0 pointer-events-none flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Share Tutorial</h3>
            <button onclick="hideShareModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="space-y-3">
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
               target="_blank"
               class="flex items-center w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fab fa-facebook-f mr-3"></i>Share on Facebook
            </a>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($tutorial->title) }}" 
               target="_blank"
               class="flex items-center w-full px-4 py-3 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition-colors">
                <i class="fab fa-twitter mr-3"></i>Share on Twitter
            </a>
            <a href="https://wa.me/?text={{ urlencode($tutorial->title . ' - ' . request()->url()) }}" 
               target="_blank"
               class="flex items-center w-full px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                <i class="fab fa-whatsapp mr-3"></i>Share on WhatsApp
            </a>
            <button onclick="copyToClipboard('{{ request()->url() }}')" 
                    class="flex items-center w-full px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-copy mr-3"></i>Copy Link
            </button>
        </div>
    </div>
</div>

<!-- Report Modal -->
<div id="reportModal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 opacity-0 pointer-events-none flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Report Issue</h3>
            <button onclick="hideReportModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="reportForm">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Issue Type</label>
                <select name="report_type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="inappropriate">Inappropriate Content</option>
                    <option value="copyright">Copyright Violation</option>
                    <option value="spam">Spam</option>
                    <option value="incorrect">Incorrect Information</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Please describe the issue..."></textarea>
            </div>
            <div class="flex space-x-3">
                <button type="button" onclick="hideReportModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-accent text-white rounded-lg hover:bg-red-600 transition-colors">
                    Submit Report
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Rating system
    document.querySelectorAll('.rating-star').forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            document.getElementById('rating-value').value = rating;
            
            document.querySelectorAll('.rating-star').forEach((s, index) => {
                if (index < rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400', 'active');
                } else {
                    s.classList.add('text-gray-300');
                    s.classList.remove('text-yellow-400', 'active');
                }
            });
        });
    });

    // Comment form submission
    document.getElementById('comment-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        try {
            const response = await fetch('{{ route("tutorials.comments.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast('Comment posted successfully!');
                location.reload(); // Reload to show new comment
            } else {
                showToast(data.message || 'Error posting comment', 'error');
            }
        } catch (error) {
            showToast('Error posting comment', 'error');
        }
    });

    // Helpful toggle
    async function toggleHelpful(commentId) {
        try {
            const response = await fetch(`{{ route("tutorials.comments.helpful") }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ comment_id: commentId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                location.reload(); // Reload to update helpful count
            }
        } catch (error) {
            showToast('Error updating helpful status', 'error');
        }
    }

    // Bookmark toggle
    @auth
    async function toggleBookmark(tutorialId) {
        try {
            const response = await fetch('{{ route("tutorials.bookmark") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ tutorial_id: tutorialId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                const icon = document.querySelector('#bookmark-btn i');
                if (data.bookmarked) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                }
                showToast(data.message);
            }
        } catch (error) {
            showToast('Error updating bookmark', 'error');
        }
    }
    @endauth

    // Modal functions
    function showShareModal() {
        document.getElementById('shareModal').classList.add('show');
    }

    function hideShareModal() {
        document.getElementById('shareModal').classList.remove('show');
    }

    function showReportModal() {
        document.getElementById('reportModal').classList.add('show');
    }

    function hideReportModal() {
        document.getElementById('reportModal').classList.remove('show');
    }

    // Copy to clipboard
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Link copied to clipboard!');
            hideShareModal();
        });
    }

    // Toast notification
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 transform transition-all duration-300 translate-x-full ${type === 'success' ? 'bg-success' : 'bg-accent'}`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => toast.classList.remove('translate-x-full'), 100);
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => document.body.removeChild(toast), 300);
        }, 3000);
    }

    // Smooth scroll to comments
    document.querySelectorAll('a[href="#comments-section"]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('comments-section').scrollIntoView({ 
                behavior: 'smooth' 
            });
        });
    });

    // Dark mode detection
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.classList.add('dark');
    }
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
        if (event.matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    });

    // Close modals on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideShareModal();
            hideReportModal();
        }
    });

    // Close modals on background click
    document.getElementById('shareModal').addEventListener('click', function(e) {
        if (e.target === this) hideShareModal();
    });

    document.getElementById('reportModal').addEventListener('click', function(e) {
        if (e.target === this) hideReportModal();
    });
    
    // Delete tutorial confirmation
    function confirmDeleteTutorial(slug, title) {
        if (confirm(`Êtes-vous sûr de vouloir supprimer le tutoriel "${title}" ?\n\nCette action supprimera également toutes les étapes, commentaires et évaluations associés.\n\nCette action est irréversible.`)) {
            // Create a form to submit the delete request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/tutorials/${slug}`;
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            // Add DELETE method
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush