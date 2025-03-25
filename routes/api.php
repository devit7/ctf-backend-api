<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChallController;
use App\Http\Controllers\Api\UserController;

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

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Challenge routes
    Route::post('/chall/submit', [ChallController::class, 'submitFlag']);
    Route::get('/chall/list', [ChallController::class, 'listChallsByUser']);
    Route::get('/leaderboard', [ChallController::class, 'leaderboard']);

    // User routes
    Route::get('/users', [UserController::class, 'list']);
    Route::put('/user/update', [UserController::class, 'update']);
});
