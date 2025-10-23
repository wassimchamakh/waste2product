# New Features Added to Dechets Module

## Database Migrations Created

### 1. Favorites System (`2025_10_03_100000_create_dechet_favorites_table.php`)
- Allows users to bookmark/save dechets for later
- Unique constraint prevents duplicate favorites
- Cascade delete when user or dechet is removed

### 2. Reviews/Ratings System (`2025_10_03_100001_create_dechet_reviews_table.php`)
- 1-5 star rating system
- Optional comment/review text
- Verified transaction flag for trust
- One review per user per dechet

### 3. Enhanced Dechets Table (`2025_10_03_100002_add_ratings_to_dechets_table.php`)
- `average_rating`: Calculated from all reviews
- `reviews_count`: Total number of reviews
- `favorites_count`: How many users favorited this

## Models Created

### DechetFavorite Model
- Relations to User and Dechet
- Handles favorite/bookmark functionality

### DechetReview Model
- Relations to User and Dechet
- Auto-validates rating between 1-5
- Supports verified transaction reviews

### Enhanced Dechet Model
New relationships:
- `reviews()` - Get all reviews
- `favorites()` - Get all favorites
- `isFavoritedBy($userId)` - Check if user favorited
- `isReviewedBy($userId)` - Check if user reviewed

New methods:
- `updateRating()` - Recalculate average rating
- `updateFavoritesCount()` - Update favorites counter
- `getRatingStarsHtml()` - Generate star HTML for display

## Controllers Created

### DechetFavoriteController
**Routes:**
- `POST /dechets/{id}/favorite` - Toggle favorite on/off
- `GET /dechets/favoris` - View all user's favorites

**Features:**
- AJAX-ready favorite toggle
- Returns JSON with updated counts
- Dedicated favorites page

### DechetReviewController
**Routes:**
- `POST /dechets/{id}/reviews` - Submit new review
- `PUT /dechets/{dechetId}/reviews/{reviewId}` - Update review
- `DELETE /dechets/{dechetId}/reviews/{reviewId}` - Delete review

**Features:**
- Prevents duplicate reviews per user
- Auto-updates dechet rating after changes
- Ownership validation

## Next Steps to Complete Integration

### 1. Update Views

#### Index Page (`resources/views/FrontOffice/dechets/index.blade.php`)
Add to hero buttons section (line 34-50):
```blade
<a
    href="{{ route('dechets.favorites') }}"
    class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-500 to-red-600 text-white px-6 py-3 rounded-lg font-medium hover:shadow-lg transition-all"
>
    <i class="fas fa-heart"></i>
    Mes favoris
</a>
```

Add to each card (after line 225 views count):
```blade
<div class="flex items-center justify-between text-sm">
    <div class="flex items-center gap-1">
        @if($dechet->reviews_count > 0)
            {!! $dechet->getRatingStarsHtml() !!}
            <span class="text-xs ml-1">({{ $dechet->reviews_count }})</span>
        @else
            <span class="text-xs text-gray-400">Pas d'avis</span>
        @endif
    </div>
    <button
        onclick="toggleFavorite({{ $dechet->id }})"
        class="favorite-btn text-gray-400 hover:text-red-500 transition-colors"
        data-dechet-id="{{ $dechet->id }}"
    >
        <i class="fas fa-heart {{ $dechet->isFavoritedBy(4) ? 'text-red-500' : '' }}"></i>
    </button>
</div>
```

Add JavaScript at bottom of file:
```javascript
<script>
function toggleFavorite(dechetId) {
    fetch(`/dechets/${dechetId}/favorite`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        const btn = document.querySelector(`[data-dechet-id="${dechetId}"] i`);
        if (data.favorited) {
            btn.classList.add('text-red-500');
        } else {
            btn.classList.remove('text-red-500');
        }
        // Show toast notification
        showToast(data.message);
    });
}

function showToast(message) {
    // Add toast notification here
    alert(message);
}
</script>
```

#### Show Page (`resources/views/FrontOffice/dechets/show.blade.php`)

Add favorite button to quick actions (after line 47):
```blade
<button
    onclick="toggleFavorite({{ $Dechet->id }})"
    class="inline-flex items-center gap-2 {{ $Dechet->isFavoritedBy(4) ? 'bg-red-500' : 'bg-gray-500' }} hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition-colors favorite-btn"
    data-dechet-id="{{ $Dechet->id }}"
>
    <i class="fas fa-heart"></i>
    <span>{{ $Dechet->isFavoritedBy(4) ? 'Favoris' : 'Ajouter aux favoris' }}</span>
</button>
```

Add reviews section before similar items (after line 201):
```blade
<!-- Reviews Section -->
<div class="mt-12">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
        Avis et Notes
        @if($Dechet->reviews_count > 0)
            <span class="text-lg font-normal text-gray-600">
                ({{ $Dechet->reviews_count }} avis • {{ number_format($Dechet->average_rating, 1) }}/5)
            </span>
        @endif
    </h2>

    <!-- Rating Summary -->
    @if($Dechet->reviews_count > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="text-center">
                <div class="text-5xl font-bold text-primary">{{ number_format($Dechet->average_rating, 1) }}</div>
                <div class="flex items-center justify-center mt-2">
                    {!! $Dechet->getRatingStarsHtml() !!}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ $Dechet->reviews_count }} avis
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Review Form -->
    @if(!$Dechet->isReviewedBy(4) && $Dechet->user_id !== 4)
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Laisser un avis</h3>
        <form action="{{ route('dechets.reviews.store', $Dechet->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Votre note
                </label>
                <div class="flex gap-2" id="rating-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" onclick="setRating({{ $i }})" class="rating-star text-3xl text-gray-300 hover:text-yellow-400 transition-colors">
                            <i class="far fa-star"></i>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating-input" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Votre commentaire (optionnel)
                </label>
                <textarea
                    name="comment"
                    rows="4"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary"
                    placeholder="Partagez votre expérience..."
                ></textarea>
            </div>

            <button type="submit" class="btn-primary px-6 py-3">
                <i class="fas fa-paper-plane mr-2"></i>
                Publier mon avis
            </button>
        </form>
    </div>
    @endif

    <!-- Reviews List -->
    @if($Dechet->reviews_count > 0)
    <div class="space-y-4">
        @foreach($Dechet->reviews()->with('user')->latest()->get() as $review)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-green-700 rounded-full flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $review->user->name }}</h4>
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                            <span>•</span>
                            <span>{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                @if($review->user_id === 4)
                <div class="flex gap-2">
                    <form action="{{ route('dechets.reviews.destroy', [$Dechet->id, $review->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Supprimer cet avis ?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
                @endif
            </div>

            @if($review->comment)
            <p class="text-gray-700 dark:text-gray-300">{{ $review->comment }}</p>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>

<script>
function setRating(rating) {
    document.getElementById('rating-input').value = rating;
    const stars = document.querySelectorAll('.rating-star i');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('far');
            star.classList.add('fas', 'text-yellow-400');
        } else {
            star.classList.remove('fas', 'text-yellow-400');
            star.classList.add('far');
        }
    });
}

function toggleFavorite(dechetId) {
    fetch(`/dechets/${dechetId}/favorite`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        const btn = document.querySelector('.favorite-btn');
        if (data.favorited) {
            btn.classList.remove('bg-gray-500');
            btn.classList.add('bg-red-500');
            btn.querySelector('span').textContent = 'Favoris';
        } else {
            btn.classList.remove('bg-red-500');
            btn.classList.add('bg-gray-500');
            btn.querySelector('span').textContent = 'Ajouter aux favoris';
        }
        alert(data.message);
    });
}
</script>
```

### 2. Create Favorites Page

Create `resources/views/FrontOffice/dechets/favorites.blade.php`:
```blade
@extends('FrontOffice.layout1.app')

@section('title', 'Mes Favoris - Waste2Product')

@section('content')
<div class="gradient-hero text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-4">
            <i class="fas fa-heart mr-3"></i>Mes Favoris
        </h1>
        <p class="text-xl opacity-90">
            Retrouvez tous les déchets que vous avez sauvegardés
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($favorites->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($favorites as $dechet)
                <!-- Reuse same card from index.blade.php -->
            @endforeach
        </div>

        <div class="mt-12">
            {{ $favorites->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <i class="fas fa-heart text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                Aucun favori
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Commencez à sauvegarder des déchets qui vous intéressent
            </p>
            <a href="{{ route('dechets.index') }}" class="btn-primary px-6 py-3">
                Parcourir les déchets
            </a>
        </div>
    @endif
</div>
@endsection
```

### 3. Run Migrations

```bash
php artisan migrate
```

## Features Summary

✅ **Favorites System**
- Toggle favorite with one click
- Dedicated favorites page
- Counter on each dechet

✅ **Rating/Review System**
- 1-5 star ratings
- Optional comments
- Average rating display
- Reviews list with user info

✅ **Enhanced UI**
- Star ratings on cards
- Favorite buttons
- Review forms
- Better engagement metrics

✅ **Backend Optimizations Needed**
- Fix N+1 queries with eager loading
- Fix image path inconsistency (Dechets vs dechets)
- Add auth middleware when ready

## Additional Features to Consider

- WhatsApp/Email sharing improvements
- Report/Flag inappropriate content
- Comparison feature
- View history tracking
- Notification system for favorites
- Advanced filters (by rating, favorites count)
