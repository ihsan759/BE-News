<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\NewsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('home', [NewsController::class, 'index']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::post('profile/update', [AuthController::class, 'update']);
    Route::prefix('news')->group(function () {
        Route::post('store', [NewsController::class, 'store']);
        Route::get('show/{id}', [NewsController::class, 'show']);
        Route::post('update/{id}', [NewsController::class, 'update']);
        Route::post('destroy/{id}', [NewsController::class, 'destroy']);
    });
});
