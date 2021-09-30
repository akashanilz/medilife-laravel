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
         Route::put('editLocation', [\App\Http\Controllers\API\PassportController::class,'editLocation']);
         Route::delete('deleteLocation/{id}', [\App\Http\Controllers\API\PassportController::class,'deleteLocation']);
         Route::get('allLocations', [\App\Http\Controllers\API\PassportController::class,'allLocations']);
        /**Client */
        Route::post('createClient', [\App\Http\Controllers\API\PassportController::class,'createClient']);
        Route::get('count', [\App\Http\Controllers\API\PassportController::class,'count']);
        Route::get('allClients', [\App\Http\Controllers\API\PassportController::class,'allClients']);
        Route::post('editClient/{id}', [\App\Http\Controllers\API\PassportController::class,'editClient']);
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
          /**Appointment */
          Route::post('getFreeUsers', [\App\Http\Controllers\API\PassportController::class,'getFreeUsers']);
          Route::post('createAppointment', [\App\Http\Controllers\API\PassportController::class,'createAppointment']);
          Route::get('timeRange', [\App\Http\Controllers\API\PassportController::class,'timeRange']);
          Route::get('appointmentsNotConfirmed', [\App\Http\Controllers\API\PassportController::class,'appointmentsNotConfirmed']);
          Route::get('confirmAppointment/{id}', [\App\Http\Controllers\API\PassportController::class,'confirmAppointment']);
          Route::get('appointmentsConfirmed', [\App\Http\Controllers\API\PassportController::class,'appointmentsConfirmed']);
          Route::get('appointmentsCompleted', [\App\Http\Controllers\API\PassportController::class,'appointmentsCompleted']);

          /**Employee -Driver get tasks */
         Route::get('getMyTasks', [\App\Http\Controllers\API\PassportController::class,'getMyTasks']);
         Route::get('findAppointment/{id}', [\App\Http\Controllers\API\PassportController::class,'findAppointment']);
         Route::get('changeAppointmentStatus/{id}', [\App\Http\Controllers\API\PassportController::class,'changeAppointmentStatus']);
         Route::get('getMyCompletedTasks', [\App\Http\Controllers\API\PassportController::class,'getMyCompletedTasks']);
         Route::put('editAppointment/{id}', [\App\Http\Controllers\API\PassportController::class,'editAppointment']);
         Route::get('mailonTheWay/{id}', [\App\Http\Controllers\API\PassportController::class,'mailOnTheWay']);

    });
});
