<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Add any other routes here that STRICTLY require a logged-in user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::put('/cards/{card}/rate', [CardController::class, 'rate']);
});

Route::put('/users/{id}', [UserController::class, 'update']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/cards', [CardController::class, 'index']);
Route::get('/cards/show/{card}', [CardController::class, 'show']);
Route::get('/cards/{lastIndex}', [CardController::class, 'infiniteLoad'])->whereNumber('lastIndex');
