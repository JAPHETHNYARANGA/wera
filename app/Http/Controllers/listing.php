<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class listing extends Controller
{
   

    public function createListing(Request $request)
    {
        $id = auth()->user()->id;
        $user = User::find($id);

        $listing = $user->listings()->create([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'amount' => $request->amount,
        ]);

        $bid = $listing->bids()->create([
            'user_id' => $id,
            'amount' => $request->input('bid_amount'),
        ]);

        return response()->json([
            'listing' => $listing,
            'bid' => $bid,
        ], 201);
    }
}
