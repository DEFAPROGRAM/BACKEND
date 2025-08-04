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
use App\Http\Controllers\SliderController;
use App\Http\Controllers\NewsSliderController;
use App\Http\Controllers\ConfiguracionController;


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

// Rutas de recursos API (públicas)
Route::apiResource('sedes', SedesController::class);
Route::apiResource('juzgados', JuzgadosController::class);
Route::apiResource('salas', SalasController::class);

// Rutas protegidas que requieren autenticación
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('reservas', ReservasController::class);
    Route::apiResource('users', UsersController::class);
});

// Rutas de reportes (protegidas)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/reportes/listados', [ReportesController::class, 'getListados']);
    Route::get('/reportes/reservas-por-fecha', [ReportesController::class, 'getReservasPorFecha']);
    Route::get('/reportes/reservas-por-usuario', [ReportesController::class, 'getReservasPorUsuario']);
    Route::get('/reportes/reservas-por-estado', [ReportesController::class, 'getReservasPorEstado']);
    Route::get('/reportes/salas-mas-solicitadas', [ReportesController::class, 'getSalasMasSolicitadas']);
    Route::get('/reportes/estadisticas-generales', [ReportesController::class, 'getEstadisticasGenerales']);

    // Rutas de exportación
    Route::post('/reportes/export/excel', [ReportesController::class, 'exportarExcel']);
    Route::post('/reportes/export/pdf', [ReportesController::class, 'exportarPdf']);
});

// Rutas públicas para consulta
Route::get('/slider', [SliderController::class, 'index']);
Route::get('/news_sliders', [NewsSliderController::class, 'index']);

// Rutas protegidas para administración (solo admin)
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Slider admin
    Route::get('/slider/admin', [SliderController::class, 'adminIndex']);
    Route::post('/slider/admin', [SliderController::class, 'store']);
    Route::put('/slider/admin/{id}', [SliderController::class, 'update']);
    Route::delete('/slider/admin/{id}', [SliderController::class, 'destroy']);

    // NewsSlider admin
    Route::get('/news_sliders/admin', [NewsSliderController::class, 'adminIndex']);
    Route::post('/news_sliders/admin', [NewsSliderController::class, 'store']);
    Route::put('/news_sliders/admin/{id}', [NewsSliderController::class, 'update']);
    Route::delete('/news_sliders/admin/{id}', [NewsSliderController::class, 'destroy']);

    // Configuración web
    Route::post('/configuracion', [ConfiguracionController::class, 'store']);
});




