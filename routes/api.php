<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\PostControllerApi;
use App\Http\Controllers\api\CommentControllerApi;
use App\Http\Controllers\api\TagControllerApi;
use App\Http\Controllers\api\Auth\PasswordControllerApi;
use App\Http\Controllers\api\Auth\RegisteredUserControllerApi;
use App\Http\Controllers\api\Auth\PasswordResetLinkControllerApi;
use App\Http\Controllers\api\Auth\AuthenticatedSessionControllerApi;

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


// Route::resource('comments', CommentControllerApi::class);
// Route::resource('posts', PostControllerApi::class);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('posts', [PostControllerApi::class, 'index']);
Route::get('posts/{post}', [PostControllerApi::class, 'show']);
Route::get('posts/create', [PostControllerApi::class, 'create']);

Route::get('comments', [CommentControllerApi::class, 'index']);
Route::get('comments/{comment}', [CommentControllerApi::class, 'show']);
Route::get('comments/create', [CommentControllerApi::class, 'create']);

Route::get('tags', [TagControllerApi::class, 'index']);
Route::get('tags/{tag}', [TagControllerApi::class, 'show']);
Route::get('tags/create', [TagControllerApi::class, 'create']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('posts', [PostControllerApi::class, 'store']);
    Route::put('posts/{post}', [PostControllerApi::class, 'update']);
    Route::delete('posts/{post}', [PostControllerApi::class, 'destroy']);

    Route::post('comments', [CommentControllerApi::class, 'store']);
    Route::delete('comments/{comment}', [CommentControllerApi::class, 'destroy']);
    Route::put('comments/{comment}', [CommentControllerApi::class, 'update']);

    Route::post('tags', [TagControllerApi::class, 'store']);
    Route::delete('tags', [TagControllerApi::class, 'destroy']);
    Route::put('tags', [TagControllerApi::class, 'update']);
});

Route::post('login', [AuthenticatedSessionControllerApi::class, 'store']);
Route::post('register', [RegisteredUserControllerApi::class, 'store']);
Route::post('forgot-password', [PasswordResetLinkControllerApi::class, 'store']);
Route::post('reset-password', [PasswordControllerApi::class, 'store']);