<?php

namespace App\Http\Controllers;

use App\Models\Favorites;
use App\Models\listings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritesController extends Controller
{
    // Add to favorites
    public function addToFavorites(Request $request, $listing_id)
    {
        try {
            $user_id = Auth::user()->userId;
            $user = User::where('userId', $user_id)->first(); // Retrieve the user
            $listing = listings::find($listing_id); // Assuming you have a Listing model
    
            if (!$listing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Listing not found',
                ], 404);
            }
    
            // Check if the listing is already in the user's favorites
            $isAlreadyFavorite = $user->favorites()->where('listing_id', $listing->id)->exists();
    
            if ($isAlreadyFavorite) {
                return response()->json([
                    'success' => false,
                    'message' => 'Listing is already in favorites',
                ], 201);
            }
    
            // Create a new record in the favorites table
            $user->favorites()->create([
                'listing_id' => $listing->id,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Listing added to favorites',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    


    // Remove from favorites
    public function removeFromFavorites($listing_id)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();

            // Find the favorite record for the given listing and user
            $favorite = Favorites::where('user_id', $user->id)
                ->where('listing_id', $listing_id)
                ->first();

            if ($favorite) {
                // Delete the favorite record
                $favorite->delete();

                return response([
                    'success' => true,
                    'message' => 'Listing removed from favorites.',
                ]);
            }

            return response([
                'success' => false,
                'message' => 'Listing is not in favorites.',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function getFavorites(Request $request)
    {
        try {
            $user = Auth::user();

            // Set the number of items per page
            $perPage = $request->input('per_page', 20);

            // Fetch the user's favorite listings along with the listing details using pagination
            $favorites = $user->favorites()->with('listing')->paginate($perPage);

            return response([
                'success' => true,
                'message' => 'Favorites fetched successfully',
                'listings' => $favorites,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

}
