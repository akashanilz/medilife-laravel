<?php

use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//
Route::post('login', [\App\Http\Controllers\API\PassportController::class,'login']);
Route::post('register', [\App\Http\Controllers\API\PassportController::class,'register']);
Route::prefix('dashboard')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::get('/', [\App\Http\Controllers\API\PassportController::class,'dashboard']);
        Route::post('createClient', [\App\Http\Controllers\API\PassportController::class,'createClient']);
        Route::get('count', [\App\Http\Controllers\API\PassportController::class,'count']);
        Route::get('allClients', [\App\Http\Controllers\API\PassportController::class,'allClient']);
    });
});

