<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SedesController;
use App\Http\Controllers\JuzgadosController;
use App\Http\Controllers\SalasController;
use App\Http\Controllers\ReservasController;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('sedes', SedesController::class);
Route::apiResource('juzgados', JuzgadosController::class);
Route::apiResource('salas', SalasController::class);
Route::apiResource('reservas', ReservasController::class);
Route::apiResource('users', UsersController::class);