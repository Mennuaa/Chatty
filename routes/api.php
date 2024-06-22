<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RecommendationsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Auth

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// User


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'getUsers']);
    Route::get('/recommendations', [RecommendationsController::class, 'recommendations']);
    Route::post('/find-user', [UserController::class, 'search'] );
    Route::put('user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::get('/conversations', [UserController::class, 'getConversations']);
    Route::post('/conversations', [ConversationController::class, 'store']);
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show']);
    Route::post('/messages',[MessageController::class, 'store']);
    
    Route::post('/anketa', [UserController::class, 'anketa']);
    Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);

    Route::post('/stories', [UserController::class, 'createStory']);
    Route::get('/stories', [UserController::class, 'getUserStories']);
    Route::get('/stories/{id}', [UserController::class, 'getStoryById']);

    Route::get('/user/{id}', [UserController::class, 'profile']);
    
    Route::post('/user/images', [UserImageController::class, 'addImage']);
    Route::get('/user/{id}/images', [UserImageController::class, 'getAllImages']);
    Route::get('/user/{id}/images/{imageId}', [UserImageController::class, 'getImage']);

    Route::post('/image/{imageId}/like', [UserImageController::class, 'likeImage']);
    Route::delete('/image/{imageId}/unlike', [UserImageController::class, 'unlikeImage']);

    // Routes for comments
    Route::post('/image/{imageId}/comment', [UserImageController::class, 'addComment']);
    Route::get('/image/{imageId}/comments', [UserImageController::class, 'getComments']);
    
});
