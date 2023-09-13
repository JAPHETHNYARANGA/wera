<?php

use App\Http\Controllers\bids;
use App\Http\Controllers\category;
use App\Http\Controllers\listing;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\user as ControllersUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Mime\MessageConverter;

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

//waiting list

Route::post('/email', [ControllersUser::class, 'addEmail']);

//Authentication

Route::post('/login', [ControllersUser::class, 'login']);
Route::post('/register', [ControllersUser::class, 'register']);
Route::post('/logout', [ControllersUser::class, 'logout'])->middleware('auth:sanctum');
Route::delete('deleteUser', [ControllersUser::class, 'deleteUser'])->middleware('auth:sanctum');
Route::get('getuser', [ControllersUser::class, 'getUser'])->middleware('auth:sanctum');
Route::put('user', [ControllersUser::class, 'updateUser'])->middleware('auth:sanctum');
Route::get('users', [ControllersUser::class, 'getUsers'])->middleware('auth:sanctum');
Route::get('profile', [ControllersUser::class, 'fetchProfile'])->middleware('auth:sanctum');
Route::get('category', [category::class, 'getCategories'])->middleware('auth:sanctum');

//Listings

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/listing', [listing::class, 'createListing']);
    Route::put('/listing/{id}', [listing::class, 'updateListing']);
    Route::get('/deletelisting/{id}', [listing::class, 'deleteListing']);
    Route::get('/userListing', [listing::class, 'getUserListings']);
    Route::get('/individuallisting/{id}', [listing::class, 'getIndividualListing']);
    Route::get('/listing', [listing::class, 'getListings']);
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
