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

            $listing = $user->listings()->create([
                'name' => $request->name,
                'description' => $request->description,
                'location' => $request->location,
                'amount' => $request->amount,
            ]);

            // $bid = $listing->bids()->create([
            //     'user_id' => $id,
            //     'amount' => $request->input('bid_amount'),
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'listing created successfully',
                'listing' => $listing,
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

            $res = $listings = listings::all();

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
