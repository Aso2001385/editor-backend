<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\LoginController;

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

Route::group(['middleware' => 'auth:api'], function () {
    Route::put('users/password', [UserController::class, 'passwordUpdate']);
    Route::post('users/search', [UserController::class, 'search']);

    Route::post('logout', [LoginController::class, 'logout']);

    Route::apiResource('projects', ProjectController::class);
    Route::post('projects/{id}/copy', [ProjectController::class, 'copy']);
    Route::post('projects/{id}/save', [ProjectController::class, 'save']);

    Route::apiResource('designs', DesignController::class);
    Route::get('designs/{id}/buy', [DesignController::class, 'buy']);
    Route::get('/designs/gacha', [DesignController::class, 'gacha']);
});

Route::post('login', [LoginController::class, 'login']);
Route::apiResource('users', UserController::class)->only(['store']);
Route::apiResource('users', UserController::class)->except(['store'])->middleware('auth');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
