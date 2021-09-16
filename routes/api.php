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
        /**Location */
        Route::post('createLocation', [\App\Http\Controllers\API\PassportController::class,'createLocation']);
        /**Clien
        Route::post('createClient', [\App\Http\Controllers\API\PassportController::class,'createClient']);
        Route::get('count', [\App\Http\Controllers\API\PassportController::class,'count']);
        Route::get('allClients', [\App\Http\Controllers\API\PassportController::class,'allClients']);
        Route::put('editClient/{id}', [\App\Http\Controllers\API\PassportController::class,'editClient']);
        Route::get('viewClient/{id}', [\App\Http\Controllers\API\PassportController::class,'viewClient']);
        Route::delete('deleteClient/{id}', [\App\Http\Controllers\API\PassportController::class,'deleteClient']);
        /**Employee */
        Route::post('createEmployee', [\App\Http\Controllers\API\PassportController::class,'createEmployee']);
        Route::get('allEmployees', [\App\Http\Controllers\API\PassportController::class,'allEmployees']);
        Route::put('editEmployee/{id}', [\App\Http\Controllers\API\PassportController::class,'editEmployee']);
        Route::get('viewEmployee/{id}', [\App\Http\Controllers\API\PassportController::class,'viewEmployee']);
        Route::delete('deleteEmployee/{id}', [\App\Http\Controllers\API\PassportController::class,'deleteEmployee']);

          /**Driver */
          Route::post('createDriver', [\App\Http\Controllers\API\PassportController::class,'createDriver']);
          Route::get('allDrivers', [\App\Http\Controllers\API\PassportController::class,'allDrivers']);
          Route::put('editDriver/{id}', [\App\Http\Controllers\API\PassportController::class,'editDriver']);
          Route::get('viewDriver/{id}', [\App\Http\Controllers\API\PassportController::class,'viewDriver']);
          Route::delete('deleteDriver/{id}', [\App\Http\Controllers\API\PassportController::class,'deleteDriver']);

    });
});

