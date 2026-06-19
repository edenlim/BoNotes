<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('root');
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/files/{path}', function (string $path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('path', '.*');

Route::fallback(function () {
    return view('root');
});
