<?php

namespace App\Http\Controllers;

use App\Models\category as ModelsCategory;
use Illuminate\Http\Request;

class category extends Controller
{
    
    public function getCategories(){
        try{


            $res = $categories = ModelsCategory::all();

            if ($res) {
                return response([
                    'success' => true,
                    'message' => 'categories fetched successfully',
                    'listings' => $categories
                ], 200);
            } else {
                return response([
                    'success' => false,
                    'message' => 'categories fetched failed',

                ], 201);
            }

        }catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
