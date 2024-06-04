<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RecommendationsController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

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
    Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);
});
