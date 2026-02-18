<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\ProfileController;
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

    // API Key Routes
    Route::get('/api-key', [ApiKeyController::class, 'index'])->name('api-key.index');
    Route::post('/api-key/generate', [ApiKeyController::class, 'generate'])->name('api-key.generate');
    Route::post('/api-key/regenerate', [ApiKeyController::class, 'regenerate'])->name('api-key.regenerate');
    Route::post('/api-key/delete', [ApiKeyController::class, 'delete'])->name('api-key.delete');

    Route::get('/download-template', [GameController::class, 'downloadTemplate'])->name('download-template');
    Route::get('/pricing', [GameController::class, 'pricing'])->name('pricing');
    Route::get('/api-tester', [GameController::class, 'apiTester'])->name('api-tester');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/subscribe', [ProfileController::class, 'subscribe'])->name('subscribe');

    // Admin Routes
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
        Route::post('/users/{user}/cancel-subscription', [\App\Http\Controllers\Admin\UserController::class, 'cancelSubscription'])->name('admin.users.cancel');
    });
});

// Public API Routes
use App\Http\Controllers\GameApiController;
use App\Http\Middleware\CheckApiKey;

Route::prefix('api')->middleware(CheckApiKey::class)->group(function () {
    Route::get('/games', [GameApiController::class, 'listGames']);
    Route::post('/check-id', [GameApiController::class, 'check']);
});
