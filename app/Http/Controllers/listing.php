<?php

namespace App\Http\Controllers;

use App\Models\listings;
use App\Models\User;
use Illuminate\Http\Request;


class listing extends Controller
{


    public function createListing(Request $request)
    {
        try {

            $id = auth()->user()->id;
            $user = User::find($id);

             $user->listings()->create([
                'name' => $request->name,
                'description' => $request->description,
                'location' => $request->location,
                'sublocation' => $request->sublocation,
                'amount' => $request->amount,
                'category_id' =>$request->category,
                'status'=>$request->status,
                'image' =>$request->image
            ]);

            return response()->json([
                'success' => true,
                'message' => 'listing created successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getListings(Request $request)
{
    try {
        $search = $request->search;
        $user = auth()->user(); // Get the authenticated user

        // Define the number of items per page
        $page = $request->input('page', 1); 
        $pageSize = 20;

        if ($search) {
            // Debug: Check the search criteria
            // dd($search);

            // Use the where method to filter based on search criteria
            $listings = Listings::where('category', 'LIKE', "%$search%")
                ->with('user:id,phone')
                ->orderBy('updated_at', 'desc')
                ->paginate($pageSize, ['*'], 'page', $page);
        } else {
            // If no search criteria, retrieve all listings with pagination
            $listings = Listings::with('user:id,phone')
                ->orderBy('updated_at', 'desc')
                ->paginate($pageSize, ['*'], 'page', $page);
        }

        return response([
            'success' => true,
            'message' => 'Listings fetched successfully',
            'listings' => $listings,
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage(),
        ], 500);
    }
}

   
    
    
    

public function getUserListings(Request $request)
{
    try {
        $id = auth()->user()->id;

        // Define the number of items per page
        $page = $request->input('page', 1); 
        $pageSize = 20;

        $listings = Listings::where('user_id', $id)
            ->with('user:id,phone')
            ->orderBy('updated_at', 'desc')
            ->paginate($pageSize, ['*'], 'page', $page);

        return response([
            'success' => true,
            'message' => 'Listings fetched successfully',
            'listings' => $listings,
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage(),
        ], 500);
    }
}


    public function getIndividualListing(Request $request, $id)
{
    try {
        $listing = listings::find($id);

        if (!$listing) {
            return response([
                'success' => false,
                'message' => 'Item not found',
            ], 404);
        }

        $userId = $listing->user_id;
        $user = User::where('id', $userId)->first();

        // Retrieve the authenticated user
        $authUser = auth()->user();

        // Check if the listing is a favorite for the authenticated user
        $isFavorite = $authUser->favorites->contains('listing_id', $listing->id);
        if($listing){
            $listing ->increment('request_count');
            // Include the 'favorite' property in the response
        return response([
            'success' => true,
            'message' => 'Item obtained successfully',
            'listing' => $listing,
            'user' => $user,
            'request_count' => $listing->request_count,
            'favorite' => $isFavorite, // Add the 'favorite' property
        ], 200);
        }else{
            return response([
                'success' => false,
                'message' => 'item fetch Failed'
            ], 201);
        }
        
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage(),
        ], 500);
    }
}



    public function updateListing(Request $request, $id)
    {
        try {
            $listing = listings::find($id);

            $listing->id;
            $listing->user_id;
            $listing->name = $request->name;
            $listing->description = $request ->description;
            $listing->location = $request ->location;
            $listing->amount = $request -> amount;
            $listing->category = $request -> category;

            $res = $listing->save();

            if ($res) {
                return response([
                    'success' => true,
                    'message' => 'item updated Successfully'
                ], 200);
            } else {
                return response([
                    'success' => false,
                    'message' => 'item update Failed'
                ], 201);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function deleteListing($id)
    {
        try {

            $listing = listings::find($id);

            $res = $listing->delete();

            if ($res) {
                return response(
                    [
                        'success' => true,
                        'message' => 'listing deleted successfully'
                    ],
                    200
                );
            } else {
                return response(
                    [
                        'success' => false,
                        'message' => 'listing delete failed'
                    ],
                    201
                );
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    } 
}
