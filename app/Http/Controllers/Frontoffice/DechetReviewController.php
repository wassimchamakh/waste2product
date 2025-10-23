<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\Dechet;
use App\Models\DechetReview;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DechetReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request, $dechetId)
    {
        $dechet = Dechet::findOrFail($dechetId);
        $userId = 4; // Replace with Auth::id()

        // Check if already reviewed
        if ($dechet->isReviewedBy($userId)) {
            return back()->with('error', '❌ Vous avez déjà évalué ce déchet');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        DechetReview::create([
            'user_id' => $userId,
            'dechet_id' => $dechetId,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        $dechet->updateRating();

        return back()->with('success', '✅ Merci pour votre avis !');
    }

    /**
     * Update review
     */
    public function update(Request $request, $dechetId, $reviewId)
    {
        $review = DechetReview::findOrFail($reviewId);
        $userId = 4; // Replace with Auth::id()

        // Check ownership
        if ($review->user_id !== $userId) {
            abort(403, 'Action non autorisée');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update($validated);
        $review->dechet->updateRating();

        return back()->with('success', '✅ Votre avis a été mis à jour');
    }

    /**
     * Delete review
     */
    public function destroy($dechetId, $reviewId)
    {
        $review = DechetReview::findOrFail($reviewId);
        $userId = 4; // Replace with Auth::id()

        // Check ownership
        if ($review->user_id !== $userId) {
            abort(403, 'Action non autorisée');
        }

        $dechet = $review->dechet;
        $review->delete();
        $dechet->updateRating();

        return back()->with('success', '✅ Votre avis a été supprimé');
    }
}
