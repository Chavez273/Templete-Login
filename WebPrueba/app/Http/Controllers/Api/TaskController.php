<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Muestra todas las tareas.
     */
    public function index()
    {
        // Devolvemos todas las tareas como JSON
        return Task::latest()->get();
    }

    /**
     * Guarda una nueva tarea.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed',
            'urgency' => 'required|in:Baja,Media,Alta',
        ]);

        $task = Task::create($validatedData);

        // Devolvemos la tarea creada y un código 201 (Created)
        return response()->json($task, 201);
    }

    /**
     * Muestra una tarea específica.
     */
    public function show(Task $task)
    {
        // Devolvemos la tarea individual como JSON
        // Laravel automáticamente encontrará la Tarea gracias al Model Binding
        return $task;
    }

    /**
     * Actualiza una tarea existente.
     */
    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed',
            'urgency' => 'required|in:Baja,Alta,Media',
        ]);

        $task->update($validatedData);

        // Devolvemos la tarea actualizada
        return response()->json($task);
    }

    /**
     * Elimina una tarea (Soft Delete).
     */
    public function destroy(Task $task)
    {
        $task->delete();

        // Devolvemos una respuesta vacía con código 204 (No Content)
        return response()->json(null, 204);
    }
}
