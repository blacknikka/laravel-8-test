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
Route::get('/memos', [ App\Http\Controllers\MemoController::class, 'showMemos'])->name('memo.all');
Route::get('/memos/{id}', [ App\Http\Controllers\MemoController::class, 'getMemosById'])->name('memo.get');;
Route::patch('/memos/{id}', [ App\Http\Controllers\MemoController::class, 'updateMemosById'])->name('memo.update');
Route::delete('/memos/{id}', [ App\Http\Controllers\MemoController::class, 'deleteMemosById'])->name('memo.delete');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', App\Http\Controllers\MeController::class);
});
