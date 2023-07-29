<?php

namespace App\Http\Controllers;

use App\Models\listings;
use Illuminate\Http\Request;

class bids extends Controller
{
    public function createBid(Request $request){
        try{
            $listingId = $request->input('listing_id');
            $userId = auth()->user()->id;

            $listing = listings::find($listingId);

            if(!$listing){
                return response()->json([
                    'success' =>false,
                    'message' =>'Listing not found'
                ], 404);
            }

                // Check if the bid amount is greater than the current bid or starting bid (if it's the first bid)
                $currentBid = $listing->bids()->max('amount');
                $startingBid = $listing->amount;
                $bidAmount = $request->input('amount');
    
                if ($bidAmount <= $currentBid || $bidAmount < $startingBid) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bid amount must be greater than the current bid or starting bid'
                    ], 400);
                }
    
                $bid = new Bids([
                    'listing_id' => $listingId,
                    'user_id' => $userId,
                    'amount' => $bidAmount
                ]);
    
                $bid->save();
    
                return response()->json([
                    'success' => true,
                    'message' => 'Bid created successfully',
                    'bid' => $bid
                ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getBidsForListing($listingId){
        try{
            $listing = listings::find($listingId);

            if(!$listing){
                return response()->json([
                    'success' =>false,
                    'message' => 'Listing not found'
                ], 404);
            }

            $bids = $listing->bids()->with('user:id,name')->get();

            return response()->json([
                'success' =>true,
                'message' => 'Bids Fetched Successfully',
                'bids' => $bids
            ], 200);

        }catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

 
}
