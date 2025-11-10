<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ViveroController;
use App\Http\Controllers\Admin\ModuloController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Auth\SetPasswordController;
use App\Http\Controllers\Api\ApiDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. RUTA PÚBLICA (PARA VISITANTES) ---
Route::get('/', function () {
    return view('welcome');
});

// --- 2. RUTAS DE AUTENTICACIÓN (login, logout, etc.) ---
// Esta línea es crucial y debe estar aquí, fuera de cualquier grupo 'auth'.
require __DIR__.'/auth.php';

// --- RUTAS PÚBLICAS PARA INVITACIÓN DE USUARIOS ---
Route::get('/establecer-contraseña/{token}', [SetPasswordController::class, 'create'])
    ->name('invitation.set-password');
Route::post('/establecer-contraseña', [SetPasswordController::class, 'store'])
    ->name('invitation.store-password');


// --- 3. RUTAS PROTEGIDAS (PARA USUARIOS QUE YA INICIARON SESIÓN) ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    // El dashboard principal que redirige según el rol
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas del perfil del usuario logueado
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // --- RUTAS PARA GESTIONAR API TOKENS ---
    Route::post('/profile/tokens', [\App\Http\Controllers\ProfileController::class, 'createToken'])->name('profile.tokens.create');
    Route::delete('/profile/tokens/{tokenId}', [\App\Http\Controllers\ProfileController::class, 'destroyToken'])->name('profile.tokens.destroy');

    // --- RUTAS DE NOTIFICACIONES ---
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [App\Http\Controllers\NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    // --- RUTA DE CONTENIDO DINÁMICO PARA DASHBOARD DEL DUEÑO ---
    Route::get('/owner/dashboard/content', [App\Http\Controllers\Api\ApiOwnerDashboardController::class, 'getDashboardContent'])->name('owner.dashboard.content');
});


// --- 4. RUTAS DE ADMINISTRACIÓN (CON PREFIJO /admin) ---
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // --- RUTAS SOLO PARA ADMIN ---
    Route::middleware(['role:Admin'])->group(function () {
        // Rutas específicas de Usuarios (papelera, etc.)
        Route::get('users/trash', [UserController::class, 'trash'])->name('users.trash');
        Route::put('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');
        Route::get('users/{user}/viveros', [UserController::class, 'showViveros'])->name('users.showViveros');

        // Rutas específicas de Viveros (papelera, etc.)
        Route::get('viveros/trash', [ViveroController::class, 'trash'])->name('viveros.trash');
        Route::put('viveros/{id}/restore', [ViveroController::class, 'restore'])->name('viveros.restore');
        Route::delete('viveros/{id}/force-delete', [ViveroController::class, 'forceDelete'])->name('viveros.forceDelete');

        // Recursos (rutas generales)
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('viveros', ViveroController::class);

        // NUEVA RUTA PARA DETALLE DEL MÓDULO (Acción "Ver Detalle")
        // Route::get('/admin/modulos/{modulo}/detail', [ModuloController::class, 'showDetail'])->name('admin.modulos.detail');
        Route::get('/modulos/{modulo}/detail', [ModuloController::class, 'showDetail'])->name('admin.modulos.detail');

        // ... Ruta de datos recientes (que ya tienes)
        Route::get('/dashboard/latest-data', [ApiDashboardController::class, 'getLatestModuleData']);

        // NUEVA RUTA PARA EL HISTORIAL DE GRÁFICOS
        Route::get('/dashboard/history/{modulo}', [ApiDashboardController::class, 'getModuleHistory']);

       
        
    });

    // --- RUTAS PARA ADMIN Y DUEÑO DE VIVERO ---
    Route::middleware(['role:Admin,Dueño de Vivero'])->group(function () {
        // Vista general de todos los módulos
        Route::get('modulos', [ModuloController::class, 'indexAll'])->name('modulos.indexAll');
        
        // Papelera de Módulos
        Route::get('viveros/{vivero}/modulos/trash', [ModuloController::class, 'trash'])->name('viveros.modulos.trash');
        Route::put('viveros/{vivero}/modulos/{moduloId}/restore', [ModuloController::class, 'restore'])->name('viveros.modulos.restore');
        Route::delete('viveros/{vivero}/modulos/{moduloId}/force-delete', [ModuloController::class, 'forceDelete'])->name('viveros.modulos.forceDelete');
        
        // Operaciones de Módulos
        Route::get('viveros/{vivero}/modulos/{modulo}/iniciar-cultivo', [ModuloController::class, 'showStartCultivoForm'])->name('viveros.modulos.startCultivoForm');
        Route::post('viveros/{vivero}/modulos/{modulo}/iniciar-cultivo', [ModuloController::class, 'startCultivo'])->name('viveros.modulos.startCultivo');
        
        // CRUD anidado de Módulos
        Route::resource('viveros.modulos', ModuloController::class)->except(['show']);

        // Reportes
        Route::get('reportes/modulo', [ReporteController::class, 'showModuleReportForm'])->name('reportes.module.form');
        Route::get('reportes/modulo/vista', [ReporteController::class, 'showModuleReportPreview'])->name('reportes.module.show');
        Route::get('reportes/modulo/descargar', [ReporteController::class, 'generateModuleReport'])->name('reportes.module.download');
        Route::get('reportes/viveros', [ReporteController::class, 'showViverosReport'])->name('reportes.viveros.show');
        Route::get('reportes/viveros/descargar', [ReporteController::class, 'downloadViverosReport'])->name('reportes.viveros.download');

         Route::get('/vivero/{vivero}/latest-data', [App\Http\Controllers\Api\ApiOwnerDashboardController::class, 'getOwnerModuleData'])->name('owner.vivero.latestData');
    });
});