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
Route::get('/user/{id}', [ControllersUser::class, 'deleteUser'])->middleware('auth:sanctum');

//Listings

Route::post('/listing', [listing::class, 'createListing'])->middleware('auth:sanctum');

Route::get('/listing', [listing::class, 'getListings']);

Route::put('/listing/{id}', [listing::class, 'updateListing'])->middleware('auth:sanctum');

Route::get('/listing/{id}', [listing::class, 'deleteListing'])->middleware('auth:sanctum');

//get listings for user
Route::get('/userListing', [listing::class, 'getUserListings'])->middleware('auth:sanctum');
