<?php

namespace App\Http\Controllers;

use App\Models\listings;
use Illuminate\Http\Request;

class bids extends Controller
{
    //

    public function test()
    {


        $listing = listings::find(1);

        $listing->bids();

      
    }
}
