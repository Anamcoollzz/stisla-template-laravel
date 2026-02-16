<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'dashboard'])->name('dashboard');

Route::get('/login', [AuthController::class, 'formLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'formRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
Route::get('/datatable', [AuthController::class, 'datatable'])->name('datatable');
