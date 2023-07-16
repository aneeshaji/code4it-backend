<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CategoryController;

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

Route::get('/', [App\Http\Controllers\Api\PostController::class, 'index'])->name('home');

Route::prefix('v1')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [LoginController::class, 'register']);

    Route::resource('posts', PostController::class);
    Route::resource('categories', CategoryController::class);

    Route::get('post/{slug}', [PostController::class, 'postDetails']);

    Route::get('post/category/{category}', [PostController::class, 'postByCategory']);

    Route::get('/recent-posts', [PostController::class, 'recentPosts']);

    Route::get('/related-posts/{slug}', [PostController::class, 'relatedPosts']);

    Route::get('/category-post-counts', [CategoryController::class, 'categoryPostCounts']);

    Route::middleware('auth:api')->group(function () {
        Route::post('user-details', [LoginController::class, 'userDetails']);
    });
});