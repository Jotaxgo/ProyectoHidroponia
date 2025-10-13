<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ViveroController;
use App\Http\Controllers\DashboardController;

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

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

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
// Route::middleware(['auth', 'verified', 'role:Admin'])
//     ->prefix('admin')
//     ->name('admin.')
//     ->group(function () {
        
//     // --- RECURSOS PRINCIPALES ---
//     Route::resource('users', UserController::class)->except(['show']);
//     // Route::resource('viveros', ViveroController::class)->except(['show']);
//     Route::get('users/{user}/viveros', [UserController::class, 'showViveros'])->name('users.showViveros'); 
//     Route::resource('viveros', ViveroController::class)->except(['show']);
//     Route::resource('viveros.modulos', \App\Http\Controllers\Admin\ModuloController::class)->except(['show']);

//     // RUTA PARA LA VISTA GENERAL DE MÓDULOS (AÑADIR)
//     Route::get('modulos', [\App\Http\Controllers\Admin\ModuloController::class, 'indexAll'])->name('modulos.indexAll');

//     // --- RUTAS PARA LA PAPELERA DE USUARIOS ---
//     Route::get('users/trash', [UserController::class, 'trash'])->name('users.trash');
//     Route::put('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
//     Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');

//     // --- RUTAS PARA LA PAPELERA DE VIVEROS ---
//     Route::get('viveros/trash', [ViveroController::class, 'trash'])->name('viveros.trash');
//     Route::put('viveros/{id}/restore', [ViveroController::class, 'restore'])->name('viveros.restore');
//     Route::delete('viveros/{id}/force-delete', [ViveroController::class, 'forceDelete'])->name('viveros.forceDelete');

//     // --- RUTAS PARA LA PAPELERA DE MÓDULOS ---
//     Route::get('viveros/{vivero}/modulos/trash', [\App\Http\Controllers\Admin\ModuloController::class, 'trash'])
//         ->name('viveros.modulos.trash');
//     Route::put('viveros/{vivero}/modulos/{moduloId}/restore', [\App\Http\Controllers\Admin\ModuloController::class, 'restore'])
//         ->name('viveros.modulos.restore');
//     Route::delete('viveros/{vivero}/modulos/{moduloId}/force-delete', [\App\Http\Controllers\Admin\ModuloController::class, 'forceDelete'])
//         ->name('viveros.modulos.forceDelete');
            
// });

// --- GRUPO DE RUTAS PARA EL PANEL DE ADMINISTRACIÓN ---
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // --- RUTAS SOLO PARA ADMIN (Gestión de Usuarios y Viveros) ---
    Route::middleware(['role:Admin'])->group(function () {
        // Usuarios
        Route::get('users/trash', [UserController::class, 'trash'])->name('users.trash');
        Route::put('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');
        Route::get('users/{user}/viveros', [UserController::class, 'showViveros'])->name('users.showViveros');

        // Viveros
        Route::get('viveros/trash', [ViveroController::class, 'trash'])->name('viveros.trash');
        Route::put('viveros/{id}/restore', [ViveroController::class, 'restore'])->name('viveros.restore');
        Route::delete('viveros/{id}/force-delete', [ViveroController::class, 'forceDelete'])->name('viveros.forceDelete');

        // --- Recursos (Rutas Generales) ---
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('viveros', ViveroController::class); // Ya no excluimos 'show'
    });

    // --- RUTAS PARA ADMIN Y DUEÑO DE VIVERO (Gestión de Módulos) ---
    Route::middleware(['role:Admin,Dueño de Vivero'])->group(function () {
        // Vista general de todos los módulos
        Route::get('modulos', [\App\Http\Controllers\Admin\ModuloController::class, 'indexAll'])->name('modulos.indexAll');

        // CRUD anidado de Módulos
        Route::resource('viveros.modulos', \App\Http\Controllers\Admin\ModuloController::class)->except(['show']);

        // Papelera de Módulos
        Route::get('viveros/{vivero}/modulos/trash', [\App\Http\Controllers\Admin\ModuloController::class, 'trash'])->name('viveros.modulos.trash');
        Route::put('viveros/{vivero}/modulos/{moduloId}/restore', [\App\Http\Controllers\Admin\ModuloController::class, 'restore'])->name('viveros.modulos.restore');
        Route::delete('viveros/{vivero}/modulos/{moduloId}/force-delete', [\App\Http\Controllers\Admin\ModuloController::class, 'forceDelete'])->name('viveros.modulos.forceDelete');

        // RUTAS PARA INICIAR CULTIVO
        Route::get('viveros/{vivero}/modulos/{modulo}/iniciar-cultivo', [\App\Http\Controllers\Admin\ModuloController::class, 'showStartCultivoForm'])->name('viveros.modulos.startCultivoForm');
        Route::post('viveros/{vivero}/modulos/{modulo}/iniciar-cultivo', [\App\Http\Controllers\Admin\ModuloController::class, 'startCultivo'])->name('viveros.modulos.startCultivo');
    });
});