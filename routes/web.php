<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\AsignacionDocenteController;

// ─── Rutas públicas ───────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.post');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ─── Panel Administrador ──────────────────────────────────────────
Route::middleware(['auth', 'role:Administrador'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))
            ->name('dashboard');
    });

// ─── Panel Docente ────────────────────────────────────────────────
Route::middleware(['auth', 'role:Docente'])
    ->prefix('docente')
    ->name('docente.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('docente.dashboard'))
            ->name('dashboard');
    });

// ─── Panel Postulante ─────────────────────────────────────────────
Route::middleware(['auth', 'role:Postulante'])
    ->prefix('postulante')
    ->name('postulante.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('postulante.dashboard'))
            ->name('dashboard');
    });




Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // ── CU3: Gestionar Postulantes ────────────────────────────────────
    Route::resource('postulantes', PostulanteController::class);

    // ── CU15: Asignar Docente a Grupo ─────────────────────────────────
    Route::get('grupos', [AsignacionDocenteController::class, 'index'])
        ->name('grupos.index');

    Route::get('grupos/{grupo}/asignar-docente', [AsignacionDocenteController::class, 'edit'])
        ->name('grupos.asignar-docente.edit');

    Route::put('grupos/{grupo}/asignar-docente', [AsignacionDocenteController::class, 'update'])
        ->name('grupos.asignar-docente.update');

    Route::patch('grupos/{grupo}/quitar-docente', [AsignacionDocenteController::class, 'quitarDocente'])
        ->name('grupos.quitar-docente');

});
