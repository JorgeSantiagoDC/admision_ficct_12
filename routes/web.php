<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

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