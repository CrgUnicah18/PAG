@extends('layouts.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-semibold text-gray-800">Solicitudes de Vacaciones</h1>
        <div class="flex space-x-3 mt-6 mb-4">
            <!-- Asignar Vacaciones (usamos icono "plus-circle" que sí carga bien) -->
            <button type="button" onclick="openVacacionesModal()"
                class="flex items-center gap-2 text-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 rounded-lg px-4 py-2 shadow-sm transition duration-300">
                <i class="fas fa-users w-4 h-4"></i>
                <span>Asignar</span>
            </button>

            <!-- Solicitar Vacaciones (edit-3 funciona bien) -->
            <a href="{{ route('admin.vacaciones.create') }}"
                class="flex items-center gap-2 text-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 rounded-lg px-4 py-2 shadow-sm transition duration-300">
                <i class="fas fa-plus w-4 h-4"></i>
                <span>Solicitar</span>
            </a>

            <!-- Generar Reporte (file-text funciona bien) -->
            <a href="{{ route('admin.vacaciones.reporte') }}"
                class="flex items-center gap-2 text-sm text-white bg-gray-700 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-400 rounded-lg px-4 py-2 shadow-sm transition duration-300">
                <i class="fas fa-list w-4 h-4"></i>
                <span>Reporte</span>
            </a>
        </div>

        {{-- Filtro por estado y nombre de empleado --}}
        <div class="mb-6">
            <form action="{{ route('admin.vacaciones.index') }}" method="GET"
                class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-2 sm:space-y-0">

                {{-- Input de búsqueda por nombre --}}
                <input type="text" name="nombreEmpleado" value="{{ request('nombreEmpleado') }}"
                    class="w-full sm:w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Buscar por nombre de empleado">

                {{-- Select de estado --}}
                <div class="flex items-center space-x-2">
                    <label for="estado" class="text-sm text-gray-700 font-medium">Estado:</label>
                    <select name="estado" id="estado"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente
                        </option>
                        <option value="pendientes_aprobacion" {{ request('estado') == 'pendientes_aprobacion' ? 'selected' : '' }}>
                            Pendiente de Aprobación</option>
                        <option value="aprobadas" {{ request('estado') == 'aprobadas' ? 'selected' : '' }}>Aprobadas</option>
                        <option value="rechazadas" {{ request('estado') == 'rechazadas' ? 'selected' : '' }}>Rechazadas
                        </option>
                    </select>
                </div>

                {{-- Botones de Filtrar y Limpiar --}}
                <div class="flex space-x-2">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2 rounded-lg transition-all duration-200">
                        Filtrar
                    </button>

                    <a href="{{ route('admin.vacaciones.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-5 py-2 rounded-lg transition-all duration-200">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabla de Vacaciones -->
        <table class="min-w-full bg-white border border-gray-200 overflow-x-auto shadow-md sm:rounded-lg mb-6"
            style="background-color: rgb(255, 255, 255);">
            <thead style="background-color: rgb(36, 94, 167);" class="text-white text-center">
                <tr>
                    <th class="rounded-tl-lg px-8 py-4 text-left text-sm font-medium">Empleado</th>
                    <th class="px-8 py-4 text-left text-sm font-medium">Fecha Inicio</th>
                    <th class="px-8 py-4 text-left text-sm font-medium">Fecha Fin</th>
                    <th class="px-8 py-4 text-left text-sm font-medium">Duración</th>
                    <th class="px-8 py-4 text-left text-sm font-medium">Periodo</th>
                    <th class="px-8 py-4 text-left text-sm font-medium">Estado</th>
                    <th class="px-8 py-4 text-left text-sm font-medium">Reintegro</th>
                    <th class="px-8 py-4 text-left text-sm font-medium">Comentario</th>
                    <th class="px-8 py-4 text-left text-sm font-medium rounded-tr-lg">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vacacionesGenerales as $vacacion)
                        <tr class="border-b border-gray-200">
                            <td class="px-8 py-4 text-sm text-gray-700">{{ $vacacion->empleado->nombre }}
                                {{ $vacacion->empleado->apellido }}
                            </td>
                            <td class="px-8 py-4 text-sm text-gray-700">{{ $vacacion->fecha_inicio }}</td>
                            <td class="px-8 py-4 text-sm text-gray-700">{{ $vacacion->fecha_fin }}</td>
                            <td class="px-8 py-4 text-sm text-gray-700">{{ $vacacion->duracion_dias }} días</td>
                            <td class="px-8 py-4 text-sm text-gray-700">{{ $vacacion->periodo }}</td>
                            <td class="px-8 py-4 text-sm text-gray-700">
                                <span class="badge bg-{{ $vacacion->estado == 'pendiente' ? 'warning' :
                    ($vacacion->estado == 'aprobadas' ? 'success' :
                        ($vacacion->estado == 'pendientes_aprobacion' ? 'primary' : 'danger')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $vacacion->estado)) }}
                                </span>
                            </td>
                            <td class="px-10 py-4 text-sm text-gray-700">{{ $vacacion->reintegro }}</td>
                            <td>{{ $vacacion->comentario ?? 'Sin comentario' }}</td>
                            <td>
                                @if($vacacion->estado == 'pendiente' || $vacacion->estado == 'pendientes_aprobacion')
                                    <button class="btn btn-outline-success btn-sm me-1" data-bs-toggle="tooltip"
                                        title="Aprobar Solicitud" onclick="confirmAction('aprobar', {{ $vacacion->id }})">
                                        <i class="fas fa-check"></i>
                                    </button>

                                    <button class="btn btn-outline-danger btn-sm me-1" data-bs-toggle="tooltip"
                                        title="Rechazar Solicitud" onclick="confirmAction('rechazar', {{ $vacacion->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                                <button class="btn btn-outline-info btn-sm" onclick="openCommentModal({{ $vacacion->id }})"
                                    title="Agregar Comentario">
                                    <i class="fas fa-comment"></i>
                                </button>
                            </td>
                        </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Paginación -->
        <div class="d-flex justify-content-center">
            {{ $vacacionesGenerales->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Nueva tabla de vacaciones propias del usuario -->
    <h2 class="text-2xl font-semibold text-gray-800 text-center p-2">Mis Solicitudes de Vacaciones</h2>

    <table class="table table-hover table-bordered text-center rounded-lg shadow-md"
        style="background-color: rgb(255, 255, 255);">
        <thead style="background-color: rgb(36, 94, 167);" class="text-white">
            <tr>
                <th class="rounded-tl-lg">Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Duración</th>
                <th>Periodo</th>
                <th>Estado</th>
                <th>Reintegro</th>
                <th class="rounded-tr-lg">Comentario</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vacacionesPropias as $vacacion)
                <tr>
                    <td>{{ $vacacion->fecha_inicio }}</td>
                    <td>{{ $vacacion->fecha_fin }}</td>
                    <td>{{ $vacacion->duracion_dias }} días</td>
                    <td>{{ $vacacion->periodo }}</td>
                    <td>
                        <span class="badge bg-{{ $vacacion->estado == 'pendiente' ? 'warning' :
                ($vacacion->estado == 'aprobadas' ? 'success' :
                    ($vacacion->estado == 'pendientes_aprobacion' ? 'primary' : 'danger')) }}">
                            {{ ucfirst(str_replace('_', ' ', $vacacion->estado)) }}
                        </span>
                    </td>
                    <td>{{ $vacacion->reintegro}}</td>
                    <td>{{ $vacacion->comentario ?? 'Sin comentario' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Paginación para la tabla de vacaciones propias -->
    {{ $vacacionesPropias->appends(request()->query())->links() }}

    <!-- Modal de Comentarios -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalLabel">Comentario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="closeCommentModal()"></button>
                </div>
                <div class="modal-body">
                    <form id="commentForm" method="POST" action="">
                        @csrf
                        <div class="mb-3">
                            <label for="comentario" class="form-label">Comentario</label>
                            <textarea class="form-control" id="comentario" name="comentario" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Comentario</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            onclick="closeCommentModal()">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Asignación de Vacaciones -->
    <div class="modal fade" id="vacacionesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Asignar Vacaciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="closeVacacionesModal()"></button>
                </div>
                <div class="modal-body">
                    <form id="vacacionesForm" method="POST" action="{{ route('admin.vacaciones.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="empleado_id" class="form-label">Empleado</label>
                            <select class="form-control" id="empleado_id" name="empleado_id">
                                @foreach($empleados as $empleado)
                                    <option value="{{ $empleado->id }}">{{ $empleado->nombre }} {{ $empleado->apellido }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tipo_permiso_id" class="form-label">Tipo de Vacación</label>
                            <select class="form-control" id="tipo_permiso_id" name="tipo_permiso_id">
                                @foreach($tiposVacaciones as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                        </div>
                        <div class="mb-3">
                            <label for="comentario" class="form-label">Comentario (Opcional)</label>
                            <textarea class="form-control" id="comentario" name="comentario" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Asignar Vacaciones</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            onclick="closeVacacionesModal()">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="closeConfirmModal()"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmMessage">¿Estás seguro de que quieres realizar esta acción?</p>
                </div>
                <div class="modal-footer">
                    <form id="confirmForm" method="POST" action="">
                        @csrf
                        <button type="submit" class="btn btn-success">Sí</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            onclick="closeConfirmModal()">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Función para abrir el modal de asignación de vacaciones
        function openVacacionesModal() {
            let vacacionesModal = new bootstrap.Modal(document.getElementById('vacacionesModal'));
            vacacionesModal.show();
        }

        // Función para cerrar el modal de asignación de vacaciones
        function closeVacacionesModal() {
            let vacacionesModalInstance = bootstrap.Modal.getInstance(document.getElementById('vacacionesModal'));
            if (vacacionesModalInstance) vacacionesModalInstance.hide();
        }

        function confirmAction(action, id) {
            let message = action === 'aprobar' ? '¿Estás seguro de que quieres aprobar esta solicitud?' : '¿Estás seguro de que quieres rechazar esta solicitud?';
            let route = action === 'aprobar' ? "{{ url('admin/vacaciones/aprobar') }}/" + id : "{{ url('admin/vacaciones/declinar') }}/" + id;

            // Asegurarse de que el mensaje se actualice correctamente
            document.getElementById('confirmMessage').textContent = message;

            // Establecer la acción del formulario
            document.getElementById('confirmForm').action = route;

            // Mostrar el modal de confirmación
            let confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            confirmModal.show();
        }

        function closeConfirmModal() {
            let confirmModalInstance = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
            if (confirmModalInstance) confirmModalInstance.hide();
        }

        function closeCommentModal() {
            let commentModalInstance = bootstrap.Modal.getInstance(document.getElementById('commentModal'));
            if (commentModalInstance) commentModalInstance.hide();
        }

        function openCommentModal(id) {
            let route = "{{ url('admin/vacaciones/addComentario') }}/" + id;
            document.getElementById('commentForm').action = route;

            // Abrir el modal de comentarios
            let commentModal = new bootstrap.Modal(document.getElementById('commentModal'));
            commentModal.show();
        }
    </script>
@endsection