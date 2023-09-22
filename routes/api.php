<?php

use App\Http\Controllers\bids;
use App\Http\Controllers\category;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\listing;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\user as ControllersUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//waiting list

Route::post('/email', [ControllersUser::class, 'addEmail']);

//Authentication

Route::post('/login', [ControllersUser::class, 'login']);
Route::get('email/verify/{id}/{hash}', [ControllersUser::class, 'verifyEmail'])->name('verification.verify');
Route::post('/register', [ControllersUser::class, 'register']);
Route::post('/logout', [ControllersUser::class, 'logout'])->middleware('auth:sanctum');
Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('reset-password', [ForgotPasswordController::class, 'reset']);

Route::middleware('auth:sanctum')->group(function(){
    Route::delete('deleteUser', [ControllersUser::class, 'deleteUser']);
    Route::get('getuser', [ControllersUser::class, 'getUser']);
    Route::put('user', [ControllersUser::class, 'updateUser']);
    Route::get('users', [ControllersUser::class, 'getUsers']);
    Route::get('profile', [ControllersUser::class, 'fetchProfile']);
    Route::get('category', [category::class, 'getCategories']);
});


//Listings
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/listing', [listing::class, 'createListing']);
    Route::put('/listing/{id}', [listing::class, 'updateListing']);
    Route::get('/deletelisting/{id}', [listing::class, 'deleteListing']);
    Route::get('/userListing', [listing::class, 'getUserListings']);
    Route::get('/individuallisting/{id}', [listing::class, 'getIndividualListing']);
    Route::get('/listing', [listing::class, 'getListings']);
    Route::post('/listing/{listing_id}/add-to-favorites', [FavoritesController::class, 'addToFavorites']);
    Route::post('/listing/{listing_id}/remove-from-favorites', [FavoritesController::class, 'removeFromFavorites']);
});



//bids
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/bids', [bids::class, 'createBid']);
    Route::get('/bids/{listing_id}', [bids::class, 'getBidsForListing']);
});

//application chat routes

Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/chatId', [MessageController::class, 'getChatId']);
    Route::get('/messages', [MessageController::class, 'getMessages']);
    Route::get('message', [MessageController::class, 'getSpecificMessage']);
    Route::post('/messages', [MessageController::class, 'index']);
    Route::get('/receiverId', [MessageController::class, 'getReceiverId']);
});
