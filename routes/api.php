<?php

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

Route::prefix('users')->group(function () {
    Route::post('', [\App\Http\Controllers\Api\AuthController::class, 'registration']);
    Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'authentication']);

});

Route::prefix('profiles')->group(function () {
    Route::get('{username}', [\App\Http\Controllers\Api\ProfileController::class, 'show']);
    Route::post('{username}/follow', [\App\Http\Controllers\Api\ProfileController::class, 'follow']);
    Route::delete('{username}/follow', [\App\Http\Controllers\Api\ProfileController::class, 'unfollow']);
});

Route::prefix('articles')->group(function () {
    Route::get('', [\App\Http\Controllers\Api\Articles\ArticleController::class, 'list']);
    Route::get('{slug}', [\App\Http\Controllers\Api\Articles\ArticleController::class, 'show']);
    Route::middleware('jwt.auth')->group(function () {
        Route::post('', [\App\Http\Controllers\Api\Articles\ArticleController::class, 'create']);
        Route::put('{slug}', [\App\Http\Controllers\Api\Articles\ArticleController::class, 'update']);
        Route::delete('{slug}', [\App\Http\Controllers\Api\Articles\ArticleController::class, 'delete']);

        Route::get('feed', [\App\Http\Controllers\Api\Articles\ArticleController::class, 'feed']);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('user', [\App\Http\Controllers\Api\UserController::class, 'authenticatedUser']);
    Route::put('user', [\App\Http\Controllers\Api\UserController::class, 'update']);
});

