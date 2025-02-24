@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-semibold mb-4">Lista de Permisos</h2>

        <!-- Filtro de estado -->
        <form method="GET" action="{{ route('admin.permisos.index') }}">
            <div class="mb-4">
                <label for="estado" class="block text-lg">Filtrar por estado:</label>
                <select name="estado" id="estado" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    onchange="this.form.submit()">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="aprobado" {{ request('estado') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                    <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                </select>
            </div>
        </form>

        <!-- Tabla de permisos -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Tipo de Permiso</th>
                    <th>Fechas</th>
                    <th>Estado</th>
                    <th>Comentario</th> <!-- Nueva columna para mostrar el comentario -->
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permisos as $permiso)
                    <tr>
                        <td>{{ $permiso->empleado->nombre }}</td>
                        <td>{{ $permiso->tipoPermiso->nombre }}</td>
                        <td>{{ $permiso->fecha_inicio }} - {{ $permiso->fecha_fin }}</td>
                        <td>
                            @if($permiso->estado == 'pendiente')
                                <span class="text-orange-600">Pendiente</span>
                            @elseif($permiso->estado == 'aprobado')
                                <span class="badge badge-success">Aprobado</span>
                            @else
                                <span class="badge badge-danger">Rechazado</span>
                            @endif
                        </td>
                        <td>
                            <!-- Mostrar el comentario si existe -->
                            @if($permiso->comentario)
                                <span class="text-gray-600">{{ $permiso->comentario }}</span>
                            @else
                                <span class="text-gray-400">No hay comentario</span>
                            @endif
                        </td>
                        <td>
                            <!-- Botón para abrir el modal de comentario -->
                            <button onclick="toggleModal('{{ $permiso->id }}')" class="btn btn-sm btn-warning p-2"
                                title="Comentario">
                                <i class="fas fa-comment-dots text-white text-xs"></i>
                            </button>

                            @if($permiso->estado === 'pendiente')
                                <!-- Botón para abrir el modal de aprobación/rechazo -->
                                <button class="btn btn-sm btn-info p-2" data-toggle="modal"
                                    data-target="#approveRejectModal{{ $permiso->id }}">
                                    <i class="fas fa-check-circle text-white text-xs"></i>
                                </button>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal de Aprobación/Rechazo -->
                    <div class="modal fade" id="approveRejectModal{{ $permiso->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="approveRejectModalLabel{{ $permiso->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="approveRejectModalLabel{{ $permiso->id }}">Aprobar o Rechazar
                                        Permiso</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    ¿Estás seguro de que deseas aprobar o rechazar esta solicitud de permiso?
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('admin.permisos.aprobar', $permiso->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Aprobar</button>
                                    </form>

                                    <form action="{{ route('admin.permisos.declinar', $permiso->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Rechazar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>

        </table>
    </div>

    <!-- Modal de Comentario (Flotante) -->
    @foreach ($permisos as $permiso)
        <div id="commentModal{{ $permiso->id }}"
            class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-[9999]">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <h2 class="text-2xl font-semibold mb-4">Comentario para el permiso de {{ $permiso->empleado->nombre }}</h2>
                <form action="{{ route('admin.permisos.addComentario', $permiso->id) }}" method="POST">
                    @csrf
                    <textarea name="comentario" class="w-full p-2 border rounded"
                        placeholder="Escribe tu comentario..."></textarea>
                    <div class="mt-4 flex justify-between">
                        <button type="button" class="btn btn-sm btn-warning p-2" onclick="toggleModal('{{ $permiso->id }}')"
                            title="Cerrar">
                            Cerrar
                        </button>
                        <button type="submit" class="btn btn-sm btn-success p-2">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

@endsection

@section('scripts')
    <script>
        window.toggleModal = function (permisoId) {
            const modal = document.getElementById('commentModal' + permisoId);
            if (modal) {
                modal.classList.toggle('hidden');
            } else {
                console.error("No se encontró el modal para el permiso ID:", permisoId);
            }
        };
    </script>
@endsection