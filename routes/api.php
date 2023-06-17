<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\VehicleController;

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

//Parking routes
Route::get('/parking-spaces', [ParkingController::class, 'getSpaces']);
Route::get('/check-bill', [ParkingController::class, 'getBill']);
Route::post('/enter-parking', [ParkingController::class, 'enter']);
Route::delete('/exit-parking', [ParkingController::class, 'exit']);
