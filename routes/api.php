<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LecturaSensorController;
use App\Http\Controllers\Api\ApiDashboardController;
use App\Http\Controllers\Api\ApiOwnerDashboardController;
use App\Http\Controllers\Api\BombaController;

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

// --- RUTA DEL ESP32 (ENTRADA DE DATOS) ---
// Esta ruta recibe los datos de los sensores.
Route::post('/lecturas', [LecturaSensorController::class, 'store'])->middleware('auth:sanctum');

// --- RUTAS DEL DASHBOARD DEL ADMIN (VISTA MACRO) ---
// Devuelve el estado de TODOS los módulos.
Route::get('/dashboard/latest-data', [ApiDashboardController::class, 'getLatestModuleData']);
// La URL a la que llama el frontend es: /api/dashboard/history/X
Route::get('/dashboard/history/{modulo}', [ApiDashboardController::class, 'getModuleHistory']);

// --- RUTA NUEVA: DASHBOARD DEL DUEÑO (VISTA FILTRADA) ---
// Devuelve el estado de los módulos filtrados por el dueño (usuario) autenticado.
Route::get('/owner/my-modules/latest-data', [ApiOwnerDashboardController::class, 'getOwnerModuleData'])->middleware('auth:sanctum');

Route::get('/bomba/{device_id}', [BombaController::class, 'estado']);
Route::post('/bomba/{device_id}', [BombaController::class, 'cambiarEstado']);