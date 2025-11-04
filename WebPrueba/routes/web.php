<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Redirección principal
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Rutas para usuarios no autenticados
Route::middleware('guest')->group(function () {
    Route::view('/login', 'login')->name('login');
    Route::view('/register', 'register')->name('register');
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');

    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Rutas para usuarios autenticados
Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'welcome')->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
