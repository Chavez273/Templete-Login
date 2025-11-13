<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Tarea (API)</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('dashboard') }}" class="nav-link">Inicio</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i>
                    <span class="ml-1">{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">
                        <p class="mb-0">{{ Auth::user()->name }}</p>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </span>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="#" class="brand-link">
            <span class="brand-text font-weight-light">Usuario</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Página Principal</p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview menu-open">
                        <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Tablas <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('tasks.index') }}" class="nav-link">
                                    <i class="fas fa-calendar nav-icon"></i>
                                    <p>Tareas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('api-tasks.index') }}" class="nav-link active">
                                    <i class="fas fa-satellite-dish"></i>
                                    <p>Tareas API</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Editar Tarea #{{ $task->id }} (API)</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('api-tasks.index') }}">Tareas (API)</a></li>
                            <li class="breadcrumb-item active">Editar</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Editar Tarea #{{ $task->id }}</h3>
                            </div>
                            <div class="card-body">
                                <form id="edit-task-form">
                                    <div id="error-container" class="alert alert-danger" style="display: none;"></div>

                                    <div class="form-group">
                                        <label for="title">Título</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                               value="{{ $task->title }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Descripción</label>
                                        <textarea class="form-control" id="description" name="description" rows="4">{{ $task->description }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="due_date">Fecha de Vencimiento</label>
                                        <input type="date" class="form-control" id="due_date" name="due_date"
                                               value="{{ $task->due_date?->format('Y-m-d') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Estado</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completado</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="urgency">Urgencia</label>
                                        <select class="form-control" id="urgency" name="urgency" required>
                                            <option value="Baja" {{ $task->urgency == 'Baja' ? 'selected' : '' }}>Baja</option>
                                            <option value="Media" {{ $task->urgency == 'Media' ? 'selected' : '' }}>Media</option>
                                            <option value="Alta" {{ $task->urgency == 'Alta' ? 'selected' : '' }}>Alta</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" id="save-button">
                                            <i class="fas fa-save"></i> Actualizar Tarea
                                        </button>
                                        <a href="{{ route('api-tasks.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Cancelar
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>

<script>
    // El ID de la tarea que estamos editando
    const taskId = {{ $task->id }};

    document.getElementById('edit-task-form').addEventListener('submit', async function(event) {
        event.preventDefault(); // Evita el envío normal

        const form = event.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        const saveButton = document.getElementById('save-button');
        const errorContainer = document.getElementById('error-container');

        saveButton.disabled = true;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
        errorContainer.style.display = 'none';

        try {
            // Usamos el método PUT (actualizar)
            const response = await fetch(`{{ url('api/tasks') }}/${taskId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                // Éxito, volver al index
                window.location.href = '{{ route('api-tasks.index') }}';
            } else {
                // Manejo de errores de validación
                const errorData = await response.json();
                if (response.status === 422 && errorData.errors) {
                    let errorMessages = '<ul>';
                    for (const field in errorData.errors) {
                        errorMessages += `<li>${errorData.errors[field][0]}</li>`;
                    }
                    errorMessages += '</ul>';
                    errorContainer.innerHTML = errorMessages;
                    errorContainer.style.display = 'block';
                } else {
                    errorContainer.innerHTML = 'Ocurrió un error inesperado.';
                    errorContainer.style.display = 'block';
                }
            }
        } catch (error) {
            console.error('Error de red:', error);
            errorContainer.innerHTML = 'Error de red al intentar actualizar la tarea.';
            errorContainer.style.display = 'block';
        } finally {
            saveButton.disabled = false;
            saveButton.innerHTML = '<i class="fas fa-save"></i> Actualizar Tarea';
        }
    });
</script>
</body>
</html>
