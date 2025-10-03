<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// --- GRUPO DE RUTAS PARA EL ADMIN ---
Route::middleware(['auth', 'verified'])->group(function () {
    // Aquí irán todas las rutas que requieren que el usuario esté logueado

    // Ahora, un sub-grupo específico para el rol de Admin
    Route::middleware(['role:Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create'); // <-- AÑADIR
        Route::post('/users', [UserController::class, 'store'])->name('users.store');       // <-- AÑADIR
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit'); // <-- AÑADIR
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update'); // <-- AÑADIR
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy'); // <-- AÑADIR
        
    });
});