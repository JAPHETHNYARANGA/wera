<?php

use App\Http\Controllers\listing;
use App\Http\Controllers\user as ControllersUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//Authentication

Route::post('/login', [ControllersUser::class, 'login']);
Route::post('/register', [ControllersUser::class, 'register']);
Route::post('/logout', [ControllersUser::class, 'logout'])->middleware('auth:sanctum');


//Listingd

Route::post('/listing', [listing::class, 'createListing'])->middleware('auth:sanctum');