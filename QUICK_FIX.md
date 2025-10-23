# Quick Fix for 500 Error

## Problem
The 500 error occurs because:
1. Migrations haven't been run yet (no `vendor` folder)
2. New database columns don't exist (`average_rating`, `reviews_count`, `favorites_count`)
3. New tables don't exist (`dechet_favorites`, `dechet_reviews`)

## Solution Options

### Option 1: Run Migrations (RECOMMENDED)
```bash
# Install dependencies first
composer install

# Then run migrations
php artisan migrate
```

### Option 2: Temporary Hide Features (If can't run migrations now)

I've already added safety checks in the views, but if errors persist:

#### Remove the favorites link temporarily:
In `resources/views/FrontOffice/dechets/index.blade.php` (lines 51-57), comment out:
```blade
{{--
<a
    href="{{ route('dechets.favorites') }}"
    class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-500 to-red-600 text-white px-6 py-3 rounded-lg font-medium hover:shadow-lg transition-all"
>
    <i class="fas fa-heart"></i>
    Mes favoris
</a>
--}}
```

#### Add this to the top of `index.blade.php` (after `@section('content')`):
```blade
@php
    use Illuminate\Support\Facades\Schema;
@endphp
```

### Option 3: Rollback to Basic Version

If you want to completely remove the new features temporarily:

1. **Remove from `app/Models/Dechet.php`**:
   - Lines 23-25 (average_rating, reviews_count, favorites_count from fillable)
   - Lines 34-36 (the new casts)
   - Lines 51-73 (reviews, favorites relationships and methods)
   - Lines 127-163 (updateRating, updateFavoritesCount, getRatingStarsHtml methods)

2. **Comment out in `routes/web.php`**:
   - Lines 6-7 (controller imports)
   - Lines 39, 48-54 (new routes)

3. **Hide features in views** (see Option 2)

## Checking Database Status

To check if tables exist:
```php
// In tinker or a controller
Schema::hasTable('dechet_favorites') // Should return true if migrated
Schema::hasColumn('dechets', 'average_rating') // Should return true if migrated
```

## Current State After My Fixes

The views now have safety checks:
- ✅ Check if columns exist before displaying ratings
- ✅ Check if tables exist before checking favorites
- ✅ Won't crash if migrations aren't run

**The page should load now**, but favorites/ratings won't show until you run migrations.

## To Fully Enable Features

1. Install composer dependencies:
   ```bash
   composer install
   ```

2. Run migrations:
   ```bash
   php artisan migrate
   ```

3. Refresh the page - all features will work!

## Error Debugging

If you still get 500 errors, check:

1. **Laravel logs**: `storage/logs/laravel.log`
2. **Browser console**: Check which endpoint is failing
3. **Route exists**: `php artisan route:list | grep dechets`

Common issues:
- Missing `vendor` folder → Run `composer install`
- Route not found → Check `routes/web.php` has new routes
- Controller not found → Check controller files exist in `app/Http/Controllers/Frontoffice/`
- Database connection → Check `.env` file

Let me know the specific error message and I can help debug!
