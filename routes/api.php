<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MarkerController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\UserController;
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




Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::resource('markers', MarkerController::class);



    Route::resource('users', UserController::class);

    Route::post('image', [ImageController::class, 'store']);
    Route::resource('comments', CommentController::class);
});
Route::prefix("data")
    ->group(function () {
        Route::get("getAllStatus", [
            MarkerController::class,
            "getAllStatus",
        ]);
    });
