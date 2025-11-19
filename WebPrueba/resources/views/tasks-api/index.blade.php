<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Tareas (API)</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <style>
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .table-actions {
            white-space: nowrap;
        }
        .pagination-custom .page-link {
            color: #007bff;
            border: 1px solid #dee2e6;
        }
        .pagination-custom .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
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
                        <h1 class="m-0">Gestión de Tareas (Modo API)</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                            <li class="breadcrumb-item active">Tareas (API)</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <!-- Alertas -->
                <div class="alert alert-success alert-dismissible" id="success-alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> ¡Éxito!</h5>
                    <span id="success-message"></span>
                </div>

                <div class="alert alert-info alert-dismissible" id="info-alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-info"></i> Información</h5>
                    <span id="info-message"></span>
                </div>

                <!-- Tabla de Tareas -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Lista de Tareas</h3>
                        <div class="card-tools">
                            <a href="{{ route('api-tasks.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Nueva Tarea
                            </a>
                            <button class="btn btn-success btn-sm" onclick="loadTasks()">
                                <i class="fas fa-sync-alt"></i> Actualizar
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Información de paginación -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-0" id="pagination-info">
                                    Cargando información...
                                </p>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="form-inline justify-content-end">
                                    <label for="perPage" class="mr-2">Mostrar:</label>
                                    <select class="form-control form-control-sm" id="perPage" onchange="changeItemsPerPage()">
                                        <option value="5">5</option>
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="width: 5%">ID</th>
                                        <th style="width: 20%">Título</th>
                                        <th style="width: 25%">Descripción</th>
                                        <th style="width: 12%">Fecha Vencimiento</th>
                                        <th style="width: 12%">Estado</th>
                                        <th style="width: 12%">Urgencia</th>
                                        <th style="width: 14%" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tasks-table-body">
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="loading-spinner mb-2"></div>
                                            <p class="text-muted mb-0">Cargando tareas...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <p class="text-muted" id="page-info">
                                    Cargando información de página...
                                </p>
                            </div>
                            <div class="col-md-6">
                                <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                    <ul class="pagination pagination-custom mb-0" id="pagination-controls">
                                        <li class="page-item disabled">
                                            <span class="page-link">Cargando...</span>
                                        </li>
                                    </ul>
                                </nav>
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
    // Token CSRF
    const csrfToken = '{{ csrf_token() }}';

    // Variables globales para la paginación
    let currentPage = 1;
    let itemsPerPage = 10;
    let totalPages = 1;
    let totalTasks = 0;

    // Opciones para los estados y urgencias
    const STATUS_OPTIONS = {
        'pending': 'Pendiente',
        'in_progress': 'En Progreso',
        'completed': 'Completada'
    };

    const URGENCY_OPTIONS = {
        'Baja': 'Baja',
        'Media': 'Media',
        'Alta': 'Alta'
    };

    // Función para formatear fecha
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES');
        } catch (e) {
            return 'N/A';
        }
    }

    // Función para generar badge de estado
    function getStatusBadge(status) {
        let badgeClass = 'badge-secondary';
        switch (status) {
            case 'pending': badgeClass = 'badge-warning'; break;
            case 'in_progress': badgeClass = 'badge-info'; break;
            case 'completed': badgeClass = 'badge-success'; break;
        }
        return `<span class="badge ${badgeClass}">${STATUS_OPTIONS[status] || 'Desconocido'}</span>`;
    }

    // Función para generar badge de urgencia
    function getUrgencyBadge(urgency) {
        let badgeClass = 'badge-secondary';
        switch (urgency) {
            case 'Baja': badgeClass = 'badge-success'; break;
            case 'Media': badgeClass = 'badge-warning'; break;
            case 'Alta': badgeClass = 'badge-danger'; break;
        }
        return `<span class="badge ${badgeClass}">${URGENCY_OPTIONS[urgency] || 'Desconocida'}</span>`;
    }

    // Cargar tareas desde API
    async function loadTasks(page = 1) {
        const tableBody = document.getElementById('tasks-table-body');
        try {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="loading-spinner mb-2"></div>
                        <p class="text-muted mb-0">Cargando tareas...</p>
                    </td>
                </tr>
            `;

            console.log(`🔍 Cargando tareas página ${page}...`);

            const response = await fetch(`/api/tasks?page=${page}&per_page=${itemsPerPage}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            if (!response.ok) {
                throw new Error(`Error ${response.status}: ${response.statusText}`);
            }

            const result = await response.json();
            console.log('📊 Datos recibidos:', result);

            if (result.success && result.data) {
                currentPage = result.pagination.current_page;
                itemsPerPage = result.pagination.per_page;
                totalPages = result.pagination.last_page;
                totalTasks = result.pagination.total;

                updatePaginationInfo(result.pagination);
                displayTasks(result.data);
                showInfoMessage(`✅ Se cargaron ${result.data.length} tareas correctamente`);
            } else {
                throw new Error(result.error || 'Error desconocido');
            }

        } catch (error) {
            console.error('❌ Error:', error);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4 text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <p class="mb-0">Error al cargar las tareas</p>
                        <small>${error.message}</small>
                        <br>
                        <button onclick="loadTasks()" class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-redo"></i> Reintentar
                        </button>
                    </td>
                </tr>
            `;
        }
    }

    // Mostrar tareas en la tabla
    function displayTasks(tasks) {
        const tableBody = document.getElementById('tasks-table-body');

        if (!tasks || tasks.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-tasks fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No hay tareas registradas</p>
                        <a href="{{ route('api-tasks.create') }}" class="btn btn-primary btn-sm mt-2">
                            <i class="fas fa-plus"></i> Crear Primera Tarea
                        </a>
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        tasks.forEach(task => {
            const editUrl = "{{ url('api-tasks') }}/" + task.id + "/edit";
            const description = task.description ?
                (task.description.length > 50 ? task.description.substring(0, 50) + '...' : task.description) :
                '<span class="text-muted">Sin descripción</span>';

            html += `
                <tr id="task-row-${task.id}">
                    <td><strong>${task.id}</strong></td>
                    <td>${task.title}</td>
                    <td>${description}</td>
                    <td>${formatDate(task.due_date)}</td>
                    <td>${getStatusBadge(task.status)}</td>
                    <td>${getUrgencyBadge(task.urgency)}</td>
                    <td class="table-actions text-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="${editUrl}" class="btn btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-danger" onclick="deleteTask(${task.id})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        tableBody.innerHTML = html;
    }

    // Actualizar información de paginación
    function updatePaginationInfo(pagination) {
        const startItem = pagination.from || 0;
        const endItem = pagination.to || 0;

        document.getElementById('pagination-info').innerHTML = `
            Mostrando <strong>${startItem}</strong> a <strong>${endItem}</strong> de <strong>${pagination.total}</strong> tareas
        `;

        document.getElementById('page-info').innerHTML = `
            Página <strong>${pagination.current_page}</strong> de <strong>${pagination.last_page}</strong>
        `;

        // Generar controles de paginación
        const paginationControls = document.getElementById('pagination-controls');
        let paginationHTML = '';

        // Botón Primera página
        paginationHTML += `
            <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="loadTasks(1)" aria-label="First">
                    <span aria-hidden="true">&laquo;&laquo;</span>
                </a>
            </li>
        `;

        // Botón Página anterior
        paginationHTML += `
            <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="loadTasks(${pagination.current_page - 1})" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        `;

        // Números de página
        const startPage = Math.max(1, pagination.current_page - 2);
        const endPage = Math.min(pagination.last_page, pagination.current_page + 2);

        for (let page = startPage; page <= endPage; page++) {
            paginationHTML += `
                <li class="page-item ${page === pagination.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="loadTasks(${page})">${page}</a>
                </li>
            `;
        }

        // Botón Página siguiente
        paginationHTML += `
            <li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="loadTasks(${pagination.current_page + 1})" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        `;

        // Botón Última página
        paginationHTML += `
            <li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="loadTasks(${pagination.last_page})" aria-label="Last">
                    <span aria-hidden="true">&raquo;&raquo;</span>
                </a>
            </li>
        `;

        paginationControls.innerHTML = paginationHTML;
    }

    // Cambiar items por página
    function changeItemsPerPage() {
        const select = document.getElementById('perPage');
        itemsPerPage = parseInt(select.value);
        currentPage = 1;
        loadTasks(currentPage);
    }

    // Eliminar tarea
    async function deleteTask(taskId) {
        if (!confirm('¿Estás seguro de que deseas eliminar esta tarea?')) return;

        try {
            console.log('🗑️ Eliminando tarea:', taskId);

            const response = await fetch(`/api/tasks/${taskId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.error || 'Error al eliminar tarea');
            }

            showSuccessMessage('✅ Tarea eliminada correctamente');
            loadTasks(currentPage);

        } catch (error) {
            console.error('❌ Error eliminar:', error);
            alert('Error al eliminar la tarea: ' + error.message);
        }
    }

    function showSuccessMessage(message) {
        document.getElementById('success-message').textContent = message;
        document.getElementById('success-alert').style.display = 'block';
        setTimeout(() => {
            document.getElementById('success-alert').style.display = 'none';
        }, 5000);
    }

    function showInfoMessage(message) {
        document.getElementById('info-message').textContent = message;
        document.getElementById('info-alert').style.display = 'block';
        setTimeout(() => {
            document.getElementById('info-alert').style.display = 'none';
        }, 4000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 Iniciando aplicación Tareas API...');
        loadTasks();
    });
</script>
</body>
</html>
