<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, TaskController};
use App\Models\Task;

Route::get('/', fn() => auth::check()
    ? redirect()->route('dashboard')
    : redirect()->route('login')
);

Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('login'))->name('login');
    Route::get('/register', fn() => view('register'))->name('register');
    Route::get('/forgot-password', fn() => view('auth.forgot-password'))->name('password.request');

    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('welcome'))->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('tasks', TaskController::class);

    // Rutas para la interfaz de API (vistas)
    Route::prefix('api-tasks')->name('api-tasks.')->group(function() {
        Route::get('/', function() {return view('tasks-api.index');})->name('index');

        Route::get('/create', function() {return view('tasks-api.create');})->name('create');

        Route::get('/{id}/edit', function($id) {$task = Task::findOrFail($id);
            return view('tasks-api.edit', compact('task')); })->name('edit');
    });
});

