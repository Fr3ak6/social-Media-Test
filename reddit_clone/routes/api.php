<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PostApiController;

// Rotta di default per utenti autenticati
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rotta di test semplice
Route::get('/test', function () {
    return response()->json([
        'message' => 'API funziona correttamente!',
        'timestamp' => now(),
        'laravel_version' => app()->version()
    ]);
});

// Rotte per i post
Route::apiResource('posts', PostApiController::class);
