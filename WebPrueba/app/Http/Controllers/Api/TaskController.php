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
        try {
            // Obtener todas las tareas con paginación
            $tasks = Task::latest()->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $tasks->items(),
                'pagination' => [
                    'current_page' => $tasks->currentPage(),
                    'last_page' => $tasks->lastPage(),
                    'per_page' => $tasks->perPage(),
                    'total' => $tasks->total(),
                    'from' => $tasks->firstItem(),
                    'to' => $tasks->lastItem(),
                ],
                'count' => $tasks->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al cargar las tareas',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Guarda una nueva tarea.
     */
    public function store(Request $request)
    {
        try {
            // Validar todos los campos que existen en tu BD
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'nullable|date',
                'status' => 'required|in:pending,in_progress,completed',
                'urgency' => 'required|in:Baja,Media,Alta',
            ]);

            $task = Task::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Tarea creada correctamente',
                'data' => $task
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al crear la tarea',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Muestra una tarea específica.
     */
    public function show(Task $task)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $task
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al cargar la tarea',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza una tarea existente.
     */
    public function update(Request $request, Task $task)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'nullable|date',
                'status' => 'required|in:pending,in_progress,completed',
                'urgency' => 'required|in:Baja,Media,Alta',
            ]);

            $task->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Tarea actualizada correctamente',
                'data' => $task
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al actualizar la tarea',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina una tarea.
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tarea eliminada correctamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al eliminar la tarea',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
