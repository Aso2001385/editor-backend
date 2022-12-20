<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\VerificationController;
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

Route::get('cors/test',[LoginController::class,'testGet']);
Route::post('cors/test',[LoginController::class,'testPost']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::put('users/password', [UserController::class, 'passwordUpdate']);
    Route::post('users/search', [UserController::class, 'search']);
    Route::get('users/designs',[UserController::class, 'designs']);
    Route::get('users/projects',[UserController::class, 'projects']);

    Route::post('logout', [LoginController::class, 'logout']);

    Route::put('projects/pages', [ProjectController::class, 'save']);
    Route::apiResource('projects', ProjectController::class);
    Route::post('projects/{id}/copy', [ProjectController::class, 'copy']);
    Route::delete('page/{id}',[ProjectController::class,'pageDelete']);
    

    Route::apiResource('designs', DesignController::class);
    Route::get('designs/{id}/buy', [DesignController::class, 'buy']);
    Route::get('/designs/gacha', [DesignController::class, 'gacha']);
});

// Route::apiResource('users', UserController::class);
Route::apiResource('users', UserController::class)->only(['store']);
Route::apiResource('users', UserController::class)->except(['store'])->middleware('auth');

Route::post('verifications/test',[VerificationController::class,'test']);
Route::post('verifications',[VerificationController::class,'verificationCheck']);
Route::get('verifications/{email}',[VerificationController::class,'reSend']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
