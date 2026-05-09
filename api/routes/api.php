<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas públicas - no requieren token
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('authors', AuthorController::class)->only(['index', 'show']);
Route::apiResource('books', BookController::class)->only(['index', 'show']);
Route::apiResource('loans', LoanController::class)->only(['index', 'show']);

// Rutas protegidas - requieren token
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('authors', AuthorController::class)->except(['index', 'show']);
    Route::apiResource('books', BookController::class)->except(['index', 'show']);
    Route::apiResource('loans', LoanController::class)->except(['index', 'show']);
    Route::apiResource('members', MemberController::class);
});
