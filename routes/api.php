<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LecturaSensorController;
use App\Http\Controllers\Api\ApiDashboardController;


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

Route::post('/lecturas', [LecturaSensorController::class, 'store'])->middleware('auth:sanctum');

Route::get('/dashboard/latest-data', [ApiDashboardController::class, 'getLatestModuleData']);

// La URL a la que llama el frontend es: /api/dashboard/history/X
Route::get('/dashboard/history/{modulo}', [ApiDashboardController::class, 'getModuleHistory']);
