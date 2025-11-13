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
        .pagination-custom .page-link {
            color: #007bff;
            border: 1px solid #dee2e6;
        }
        .pagination-custom .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }
        .pagination-custom .page-link:hover {
            color: #0056b3;
            background-color: #e9ecef;
        }
        .table-actions {
            white-space: nowrap;
        }
        .status-badge {
            font-size: 0.85em;
        }
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
    </style>
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
                <div class="alert alert-success alert-dismissible" id="success-alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> ¡Éxito!</h5>
                    <span id="success-message"></span>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Lista de Tareas</h3>
                        <div class="card-tools">
                            <a href="{{ route('api-tasks.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Nueva Tarea
                            </a>
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
                                    <select class="form-control form-control-sm" id="perPage">
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

                        <!-- Paginación Mejorada -->
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
    // Token CSRF (¡MUY IMPORTANTE para que la API funcione!)
    const csrfToken = '{{ csrf_token() }}';

    // Variables globales para la paginación
    let allTasks = [];
    let currentPage = 1;
    let itemsPerPage = 10;
    let totalPages = 1;

    // Opciones para los 'selects' (copiadas de tu Modelo Task)
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

    // ================================================
    // FUNCIÓN DE FECHA CORREGIDA (Versión Final Definitiva)
    // ================================================
    function formatDate(dateString) {
        if (!dateString) {
            return 'N/A';
        }

        const dateOnlyString = dateString.substring(0, 10);
        const parts = dateOnlyString.split('-');

        if (parts.length !== 3) {
            console.warn('Formato de fecha inesperado:', dateOnlyString);
            return 'Invalid Date';
        }

        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }

    // Función para generar la insignia de estado
    function getStatusBadge(status) {
        let badgeClass = 'badge-secondary';
        switch (status) {
            case 'pending': badgeClass = 'badge-warning'; break;
            case 'in_progress': badgeClass = 'badge-info'; break;
            case 'completed': badgeClass = 'badge-success'; break;
        }
        return `<span class="badge ${badgeClass} status-badge">${STATUS_OPTIONS[status] || 'Desconocido'}</span>`;
    }

    // Función para generar la insignia de urgencia
    function getUrgencyBadge(urgency) {
        let badgeClass = 'badge-secondary';
        switch (urgency) {
            case 'Baja': badgeClass = 'badge-success'; break;
            case 'Media': badgeClass = 'badge-warning'; break;
            case 'Alta': badgeClass = 'badge-danger'; break;
        }
        return `<span class="badge ${badgeClass} status-badge">${URGENCY_OPTIONS[urgency] || 'Desconocida'}</span>`;
    }

    // --- FUNCIÓN PRINCIPAL: Cargar Tareas ---
    async function loadTasks() {
        const tableBody = document.getElementById('tasks-table-body');
        try {
            // Mostrar estado de carga
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="loading-spinner mb-2"></div>
                        <p class="text-muted mb-0">Cargando tareas...</p>
                    </td>
                </tr>
            `;

            // Llama a la ruta de la API que creamos
            const response = await fetch('{{ route('api.tasks.index') }}');
            if (!response.ok) throw new Error('Error al cargar tareas');

            allTasks = await response.json();

            // Actualizar paginación
            updatePagination();

            // Mostrar primera página
            displayCurrentPage();

        } catch (error) {
            console.error(error);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4 text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <p class="mb-0">Error al cargar las tareas.</p>
                        <small>${error.message}</small>
                    </td>
                </tr>
            `;
        }
    }

    // --- FUNCIÓN: Mostrar página actual ---
    function displayCurrentPage() {
        const tableBody = document.getElementById('tasks-table-body');

        if (allTasks.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-tasks fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No hay tareas registradas.</p>
                        <a href="{{ route('api-tasks.create') }}" class="btn btn-primary btn-sm mt-2">
                            <i class="fas fa-plus"></i> Crear Primera Tarea
                        </a>
                    </td>
                </tr>
            `;
            return;
        }

        // Calcular tareas para la página actual
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, allTasks.length);
        const currentTasks = allTasks.slice(startIndex, endIndex);

        let html = '';
        currentTasks.forEach(task => {
            // URL de edición para la vista API
            const editUrl = `{{ url('api-tasks') }}/${task.id}/edit`;

            const description = task.description ?
                `<span title="${task.description}">${task.description.length > 50 ? task.description.substring(0, 50) + '...' : task.description}</span>` :
                '<span class="text-muted">Sin descripción</span>';

            const dueDate = task.due_date ?
                `<span class="badge badge-light border">${formatDate(task.due_date)}</span>` :
                '<span class="text-muted">N/A</span>';

            html += `
                <tr id="task-row-${task.id}">
                    <td><strong>${task.id}</strong></td>
                    <td>${task.title}</td>
                    <td>${description}</td>
                    <td>${dueDate}</td>
                    <td>${getStatusBadge(task.status)}</td>
                    <td>${getUrgencyBadge(task.urgency)}</td>
                    <td class="table-actions text-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="${editUrl}" class="btn btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-danger btn-delete" data-id="${task.id}" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        tableBody.innerHTML = html;
    }

    // --- FUNCIÓN: Actualizar controles de paginación ---
    function updatePagination() {
        totalPages = Math.ceil(allTasks.length / itemsPerPage);

        // Actualizar información de paginación
        const startItem = allTasks.length > 0 ? (currentPage - 1) * itemsPerPage + 1 : 0;
        const endItem = Math.min(currentPage * itemsPerPage, allTasks.length);

        document.getElementById('pagination-info').innerHTML = `
            Mostrando <strong>${startItem}</strong> a <strong>${endItem}</strong> de <strong>${allTasks.length}</strong> tareas
        `;

        document.getElementById('page-info').innerHTML = `
            Página <strong>${currentPage}</strong> de <strong>${totalPages}</strong>
        `;

        // Generar controles de paginación
        const paginationControls = document.getElementById('pagination-controls');

        if (totalPages <= 1) {
            paginationControls.innerHTML = '';
            return;
        }

        let paginationHTML = '';

        // Botón Primera página
        paginationHTML += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(1)" aria-label="First">
                    <span aria-hidden="true">&laquo;&laquo;</span>
                </a>
            </li>
        `;

        // Botón Página anterior
        paginationHTML += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${currentPage - 1})" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        `;

        // Números de página (mostrar páginas alrededor de la actual)
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);

        for (let page = startPage; page <= endPage; page++) {
            paginationHTML += `
                <li class="page-item ${page === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${page})">${page}</a>
                </li>
            `;
        }

        // Botón Página siguiente
        paginationHTML += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${currentPage + 1})" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        `;

        // Botón Última página
        paginationHTML += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${totalPages})" aria-label="Last">
                    <span aria-hidden="true">&raquo;&raquo;</span>
                </a>
            </li>
        `;

        paginationControls.innerHTML = paginationHTML;
    }

    // --- FUNCIÓN: Cambiar página ---
    function changePage(page) {
        if (page < 1 || page > totalPages || page === currentPage) return;

        currentPage = page;
        displayCurrentPage();
        updatePagination();

        // Scroll suave hacia arriba de la tabla
        document.querySelector('.card-body').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    // --- FUNCIÓN: Cambiar items por página ---
    function changeItemsPerPage() {
        const select = document.getElementById('perPage');
        itemsPerPage = parseInt(select.value);
        currentPage = 1; // Volver a la primera página
        displayCurrentPage();
        updatePagination();
    }

    // --- FUNCIÓN: Borrar Tarea ---
    async function deleteTask(taskId) {
        if (!confirm('¿Estás seguro de que deseas eliminar esta tarea?')) return;

        try {
            const response = await fetch(`{{ url('api/tasks') }}/${taskId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Error al eliminar la tarea');

            // Eliminar la tarea del array local
            allTasks = allTasks.filter(task => task.id !== taskId);

            // Si la página actual queda vacía, retroceder una página
            if (allTasks.length > 0 && (currentPage - 1) * itemsPerPage >= allTasks.length) {
                currentPage = Math.max(1, currentPage - 1);
            }

            // Actualizar la vista
            displayCurrentPage();
            updatePagination();

            // Mostrar mensaje de éxito
            document.getElementById('success-message').innerText = '¡Tarea eliminada exitosamente!';
            document.getElementById('success-alert').style.display = 'block';

            // Ocultar mensaje después de 5 segundos
            setTimeout(() => {
                document.getElementById('success-alert').style.display = 'none';
            }, 5000);

        } catch (error) {
            console.error(error);
            alert('Error al eliminar la tarea.');
        }
    }

    // --- EVENT LISTENERS ---

    // 1. Cargar tareas cuando el documento esté listo
    document.addEventListener('DOMContentLoaded', function() {
        loadTasks();

        // Configurar evento para el selector de items por página
        document.getElementById('perPage').addEventListener('change', changeItemsPerPage);
    });

    // 2. Escuchar clics en los botones de borrado (Delegación de eventos)
    document.getElementById('tasks-table-body').addEventListener('click', function(event) {
        const deleteButton = event.target.closest('.btn-delete');
        if (deleteButton) {
            const taskId = deleteButton.dataset.id;
            deleteTask(taskId);
        }
    });

    // Auto-ocultar alertas después de 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const successAlert = document.getElementById('success-alert');
            if (successAlert.style.display !== 'none') {
                successAlert.style.display = 'none';
            }
        }, 5000);
    });

</script>
</body>
</html>
