<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\ChapterController;
use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ReviewBookController;
use App\Http\Controllers\Api\ReviewChapterController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes

# Auth
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

# Logged in user routes
Route::group(['middleware' => 'auth:api'], function () {
    # Public
    Route::apiResource('book-reviews', ReviewBookController::class);
    Route::apiResource('chapter-reviews', ReviewChapterController::class);
    Route::apiResource('chapters', ChapterController::class);
    Route::apiResource('genres', GenreController::class);
    Route::apiResource('books', BookController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
    Route::apiResource('users', UserController::class)->except(['store']);

    # Private 
    Route::group(['middleware' => 'isAdmin'], function () {
        # Role Routes
    });
});
// Private Routes