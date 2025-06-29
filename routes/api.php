<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SedesController;
use App\Http\Controllers\JuzgadosController;
use App\Http\Controllers\SalasController;
use App\Http\Controllers\ReservasController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\Auth\AuthController;


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

// Rutas de autenticación
Route::post('/auth/login', [AuthController::class, 'login']);

// Rutas protegidas que requieren autenticación
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
});

// Rutas de recursos API
Route::apiResource('sedes', SedesController::class);
Route::apiResource('juzgados', JuzgadosController::class);
Route::apiResource('salas', SalasController::class);
Route::apiResource('reservas', ReservasController::class);
Route::apiResource('users', UsersController::class);

// Rutas de reportes
Route::get('/reportes/listados', [ReportesController::class, 'getListados']);
Route::get('/reportes/reservas-por-fecha', [ReportesController::class, 'getReservasPorFecha']);
Route::get('/reportes/reservas-por-usuario', [ReportesController::class, 'getReservasPorUsuario']);
Route::get('/reportes/reservas-por-estado', [ReportesController::class, 'getReservasPorEstado']);
Route::get('/reportes/salas-mas-solicitadas', [ReportesController::class, 'getSalasMasSolicitadas']);
Route::get('/reportes/estadisticas-generales', [ReportesController::class, 'getEstadisticasGenerales']);

// Rutas de exportación
Route::post('/reportes/export/excel', [ReportesController::class, 'exportarExcel']);
Route::post('/reportes/export/pdf', [ReportesController::class, 'exportarPdf']);




