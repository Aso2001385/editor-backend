<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PageController;
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



Route::middleware('auth:sanctum')->group(function () {

    Route::get('users/designs',[UserController::class, 'designs']);
    Route::get('users/projects',[UserController::class, 'projects']);
    Route::apiResource('users', UserController::class)->except(['store']);
    Route::put('users/password', [UserController::class, 'passwordUpdate']);
    Route::post('users/search', [UserController::class, 'search']);

    Route::post('logout', [LoginController::class, 'logout']);

    Route::put('projects/pages', [ProjectController::class, 'save']);
    Route::apiResource('projects', ProjectController::class);

    Route::post('projects/{id}/copy', [ProjectController::class, 'copy']);
    Route::get('projects/{uuid}/pages/{number}', [PageController::class, 'show']);

    Route::put('pages/{id}',[PageController::class,'save']);
    Route::delete('pages/{id}',[ProjectController::class,'pageDelete']);


    Route::apiResource('designs', DesignController::class);
    Route::get('designs/{id}/buy', [DesignController::class, 'buy']);

    // Route::get('/designs/gacha', [DesignController::class, 'gacha']);
});

Route::post('users', [UserController::class,'store']);
Route::post('users/register', [UserController::class,'register']);

Route::post('verifications/test',[VerificationController::class,'test']);
Route::post('verifications',[VerificationController::class,'verificationCheck']);
Route::get('verifications/{email}',[VerificationController::class,'reSend']);

Route::get('projects/export/{id}',[ProjectController::class, 'export']);

Route::get('cors/test',[LoginController::class,'testGet']);
Route::post('cors/test',[LoginController::class,'testPost']);