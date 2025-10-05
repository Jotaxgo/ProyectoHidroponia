<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ViveroController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Ruta de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Ruta del dashboard principal de Breeze
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas del perfil de usuario de Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- RUTAS PARA ESTABLECER CONTRASEÑA POR INVITACIÓN ---
Route::get('/establecer-contraseña/{token}', [\App\Http\Controllers\Auth\SetPasswordController::class, 'create'])
    ->name('invitation.set-password');

Route::post('/establecer-contraseña', [\App\Http\Controllers\Auth\SetPasswordController::class, 'store'])
    ->name('invitation.store-password');

// Incluye las rutas de autenticación (login, register, etc.)
require __DIR__.'/auth.php';


// --- GRUPO DE RUTAS PARA EL ADMIN ---
Route::middleware(['auth', 'verified', 'role:Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
    // --- RECURSOS PRINCIPALES ---
    Route::resource('users', UserController::class)->except(['show']);
    // Route::resource('viveros', ViveroController::class)->except(['show']);
    // Y AÑADE ESTE BLOQUE EN SU LUGAR:
    Route::resource('viveros', ViveroController::class)->except(['show']);
    Route::resource('viveros.modulos', \App\Http\Controllers\Admin\ModuloController::class)->except(['show']);

    // --- RUTAS PARA LA PAPELERA DE USUARIOS ---
    Route::get('users/trash', [UserController::class, 'trash'])->name('users.trash');
    Route::put('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');

    // --- RUTAS PARA LA PAPELERA DE VIVEROS ---
    Route::get('viveros/trash', [ViveroController::class, 'trash'])->name('viveros.trash');
    Route::put('viveros/{id}/restore', [ViveroController::class, 'restore'])->name('viveros.restore');
    Route::delete('viveros/{id}/force-delete', [ViveroController::class, 'forceDelete'])->name('viveros.forceDelete');

    // --- RUTAS PARA LA PAPELERA DE MÓDULOS ---
    Route::get('viveros/{vivero}/modulos/trash', [\App\Http\Controllers\Admin\ModuloController::class, 'trash'])
        ->name('viveros.modulos.trash');
    Route::put('viveros/{vivero}/modulos/{moduloId}/restore', [\App\Http\Controllers\Admin\ModuloController::class, 'restore'])
        ->name('viveros.modulos.restore');
    Route::delete('viveros/{vivero}/modulos/{moduloId}/force-delete', [\App\Http\Controllers\Admin\ModuloController::class, 'forceDelete'])
        ->name('viveros.modulos.forceDelete');
            
});