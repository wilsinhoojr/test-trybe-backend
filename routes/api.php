<?php

use App\Http\Controllers\Apis\Auth\AuthController;
use App\Http\Controllers\Apis\Post\PostController;
use App\Http\Controllers\Apis\User\UserController;
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

Route::post('login', [AuthController::class, 'login']);
Route::post('user', [AuthController::class, 'user']);
Route::group([
                 'middleware' => ['jwt.verify'],
             ], function() {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::delete('user/me', [UserController::class, 'me']);
    Route::get('user', [UserController::class, 'getUsers']);
    Route::get('user/{userId}', [UserController::class, 'getUser']);
    Route::get('post/search', [PostController::class, 'search'])->name('postsearch');
    Route::resource('post', PostController::class)->names('post')->except('create', 'edit');
});
