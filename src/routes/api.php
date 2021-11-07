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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// memo
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::apiResource('memos', App\Http\Controllers\MemoController::class)->only(['index', 'show', 'update', 'destroy']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', App\Http\Controllers\MeController::class)->name('user.me');
});
