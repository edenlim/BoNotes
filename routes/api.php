<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/cards', [CardController::class, 'index']);
Route::get('/users', [UserController::class, 'index']);
