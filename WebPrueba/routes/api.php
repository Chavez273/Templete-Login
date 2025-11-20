<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\AuthController;

// Rutas públicas de autenticación
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/logout', [AuthController::class, 'logout']);

// Rutas de tareas con nombres
Route::get('/tasks', [TaskController::class, 'index'])->name('api.tasks.index');
Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('api.tasks.show');
Route::post('/tasks', [TaskController::class, 'store'])->name('api.tasks.store');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('api.tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('api.tasks.destroy');
