<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\Dechet;
use App\Models\DechetFavorite;
use Illuminate\Http\Request;

class DechetFavoriteController extends Controller
{
    /**
     * Toggle favorite
     */
    public function toggle($id)
    {
        $dechet = Dechet::findOrFail($id);
        $userId = 4; // Replace with Auth::id() when authentication is ready

        $favorite = DechetFavorite::where('user_id', $userId)
                                   ->where('dechet_id', $id)
                                   ->first();

        if ($favorite) {
            $favorite->delete();
            $dechet->updateFavoritesCount();

            return response()->json([
                'success' => true,
                'favorited' => false,
                'favorites_count' => $dechet->favorites_count,
                'message' => 'Retiré des favoris'
            ]);
        } else {
            DechetFavorite::create([
                'user_id' => $userId,
                'dechet_id' => $id,
            ]);
            $dechet->updateFavoritesCount();

            return response()->json([
                'success' => true,
                'favorited' => true,
                'favorites_count' => $dechet->favorites_count,
                'message' => 'Ajouté aux favoris'
            ]);
        }
    }

    /**
     * List user's favorites
     */
    public function index()
    {
        $userId = 4; // Replace with Auth::id()

        $favorites = Dechet::whereHas('favorites', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with(['category', 'user'])
        ->where('is_active', true)
        ->latest()
        ->paginate(12);

        return view('FrontOffice.dechets.favorites', compact('favorites'));
    }
}
