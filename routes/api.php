<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;

// - Login
Route::post('/login', [AuthController::class, 'login']);

// - Protected Routes; Requires user logged in
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/cards', [CardController::class, 'store']);    
    Route::put('/cards/{card}', [CardController::class, 'update']);
    Route::put('/cards/{card}/rate', [CardController::class, 'rate']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('/cards/{card}', [CardController::class, 'update']);
    Route::put('/cards/{card}/rate', [CardController::class, 'rate']);
    Route::delete('/cards/{card}', [CardController::class, 'destroy']);
    Route::get('/cards/{lastIndex}', [CardController::class, 'infiniteLoad'])->whereNumber('lastIndex');
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::get('/users', [UserController::class, 'index']);
});

// - Public Routes; No login required
Route::get('/cards', [CardController::class, 'index']);
Route::get('/cards/show/{card}', [CardController::class, 'show']);
Route::get('/cards/{lastIndex}', [CardController::class, 'infiniteLoad'])->whereNumber('lastIndex');
