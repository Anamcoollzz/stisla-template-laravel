<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

// Guest routes (only accessible when not authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'formLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'formRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes (require login)
Route::middleware('auth')->group(function () {
    Route::get('/', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AuthController::class, 'dashboard']);
    Route::get('/datatable', [AuthController::class, 'datatable'])->name('datatable');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Game ID Checker
    Route::get('/game/check', [GameController::class, 'index'])->name('game.check');
    Route::post('/game/check-id', [GameController::class, 'checkId'])->name('game.check-id');
});
