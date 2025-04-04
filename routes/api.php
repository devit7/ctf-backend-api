<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChallController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\DiscordWebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public routes
Route::get('/test', function () {
    return response()->json([
        'message' => 'Hello, this is a test route!'
    ]);
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
Route::post('/email/resend', [AuthController::class, 'resendVerification'])->name('verification.resend');
Route::get('/users', [UserController::class, 'list']);
Route::get('/leaderboard', [ChallController::class, 'leaderboard']);
Route::get('/chall/categories', [ChallController::class, 'getCategorys']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Challenge routes
    Route::post('/chall/submit', [ChallController::class, 'submitFlag']);
    Route::get('/chall/list', [ChallController::class, 'listChallsByUser']);
    Route::get('/chall/submissions/{id}', [ChallController::class, 'getSubmissionsByChall']);

    // User routes
    Route::get('/user/auth', [AuthController::class, 'getUserAuthenticated']);
    Route::get('/user/profile', [UserController::class, 'getUserWithRankAndScore']);
    Route::put('/user/update', [UserController::class, 'update']);
    Route::get('/user/stats', [UserController::class, 'getAllCountCategorySolvedByUser']);

    //isAuthenticated
    Route::get('/isAuthenticated', function (Request $request) {
        return response()->json([
            'status' => true,
            'message' => 'User is authenticated',
        ]);
    });


});
