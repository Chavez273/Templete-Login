<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Models\Task;

Route::prefix('api')->name('api.')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

Route::prefix('api-tasks')->name('api-tasks.')->group(function() {
    Route::get('/', function() { return view('tasks-api.index'); })->name('index');

    Route::get('/create', function() {return view('tasks-api.create');})->name('create');

    Route::get('/{task}/edit', function(Task $task) {
        // Pasamos la tarea para saber cuál editar
        return view('tasks-api.edit', compact('task'));
    })->name('edit');
});
