<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
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

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name("login");
Route::get('/images', [ImageController::class, 'index'])->name("public-images");

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/image/{id}', [ImageController::class, 'show']);
    Route::post('/image', [ImageController::class, 'store']);
    Route::post('/image/{id}/change-privacy', [ImageController::class, 'changePrivacy']);

    Route::get('/user/images', [UserController::class, 'images'])->name("personal-images");

    Route::post('/tag', [TagController::class, 'store']);

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
