<?php

namespace App\Http\Controllers;

use App\Models\listings;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    // Add to favorites
public function addToFavorites(Request $request, $listing_id)
{
    try {
        $user = auth()->user();
        $listing = listings::find($listing_id);

        // Check if the listing is already in the user's favorites
        if (!$user->favorites()->where('listing_id', $listing_id)->exists()) {
            $user->favorites()->attach($listing_id);
            return response([
                'success' => true,
                'message' => 'Listing added to favorites successfully',
            ], 200);
        }

        return response([
            'success' => false,
            'message' => 'Listing is already in favorites',
        ], 201);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage(),
        ], 500);
    }
}

// Remove from favorites
public function removeFromFavorites(Request $request, $listing_id)
{
    try {
        $user = auth()->user();
        $listing = listings::find($listing_id);

        // Check if the listing is in the user's favorites
        if ($user->favorites()->where('listing_id', $listing_id)->exists()) {
            $user->favorites()->detach($listing_id);
            return response([
                'success' => true,
                'message' => 'Listing removed from favorites successfully',
            ], 200);
        }

        return response([
            'success' => false,
            'message' => 'Listing is not in favorites',
        ], 201);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage(),
        ], 500);
    }
}

}
