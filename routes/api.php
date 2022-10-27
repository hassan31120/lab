<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\TypeController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
    Route::post('/add_order', [OrderController::class, 'store']);
    Route::post('/edit_order/{id}', [OrderController::class, 'update']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//doctors
Route::get('/doctors', [DoctorController::class, 'index']);
Route::post('/add_doctor', [DoctorController::class, 'store']);
Route::post('/edit_doctor/{id}', [DoctorController::class, 'update']);
Route::post('/del_doctor/{id}', [DoctorController::class, 'destroy']);

//colors
Route::get('/colors', [ColorController::class, 'index']);
Route::post('/add_color', [ColorController::class, 'store']);
Route::post('/edit_color/{id}', [ColorController::class, 'update']);
Route::post('/del_color/{id}', [ColorController::class, 'destroy']);

//types
Route::get('/types', [TypeController::class, 'index']);
Route::post('/add_type', [TypeController::class, 'store']);
Route::post('/edit_type/{id}', [TypeController::class, 'update']);
Route::post('/del_type/{id}', [TypeController::class, 'destroy']);

//orders
Route::get('/orders', [OrderController::class, 'index']);
Route::post('/del_order/{id}', [OrderController::class, 'destroy']);
