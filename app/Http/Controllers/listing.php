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
                'amount' => $request->amount,
                'category_id' =>$request->category,
                'status'=>$request->status
            ]);

            // $bid = $listing->bids()->create([
            //     'user_id' => $id,
            //     'amount' => $request->input('bid_amount'),
            // ]);

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
    
            if ($search) {
                $listings = listings::where('category', 'LIKE', "%$search%")
                                    ->with('user:id,phone')
                                    ->orderBy('updated_at', 'desc') // Add orderBy clause for most recently updated
                                    ->get();
            } else {
                $listings = listings::with('user:id,phone')
                                    ->orderBy('updated_at', 'desc') // Add orderBy clause for most recently updated
                                    ->get();
            }
    
            return response([
                'success' => true,
                'message' => 'listings fetched successfully',
                'listings' => $listings
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    

    public function getUserListings(){
        try {
            $id = auth()->user()->id;
            // $user = User::find($id);

            $res = $listings = listings::where('user_id', $id)->with('user:id,phone')->get();

            if ($res) {
                return response([
                    'success' => true,
                    'message' => 'listings fetched successfully',
                    'listings' => $listings
                ], 200);
            } else {
                return response([
                    'success' => false,
                    'message' => 'listings fetched failed',

                ], 201);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getIndividualListing($id){
        try{
            $res = $listing = listings::find($id);

            if($res){
                return response([
                    'success' => true,
                    'message' => 'item obtained Successfully',
                    'listing'=>$listing
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
                'message' => $th->getMessage()
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
