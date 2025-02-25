@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-semibold mb-4">Solicitudes de Vacaciones</h2>
        <a href="{{ route('admin.vacaciones.create') }}" class="btn btn-primary mb-2">Asignar Vacaciones</a>

        <!-- Filtro de estado -->
        <form method="GET" action="{{ route('admin.vacaciones.index') }}">
            <div class="mb-4">
                <label for="estado" class="block text-lg">Filtrar por estado:</label>
                <select name="estado" id="estado" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    onchange="this.form.submit()">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="aprobado" {{ request('estado') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                    <option value="rechazado" {{ request('estado') == 'rechazada' ? 'selected' : '' }}>Rechazado</option>
                </select>
            </div>
        </form>

        <!-- Tabla de vacaciones -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Fechas</th>
                    <th>Duración</th>
                    <th>Estado</th>
                    <th>Comentario</th> <!-- Nueva columna para mostrar el comentario -->
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vacaciones as $vacacion)
                    <tr>
                        <td>{{ $vacacion->empleado->nombre }} {{ $vacacion->empleado->apellido }}</td>
                        <td>{{ $vacacion->fecha_inicio }} - {{ $vacacion->fecha_fin }}</td>
                        <td>{{ $vacacion->duracion_dias }} días</td>
                        <td>
                            @if($vacacion->estado == 'pendiente')
                                <span class="text-orange-600">Pendiente</span>
                            @elseif($vacacion->estado == 'aprobado')
                                <span class="badge badge-success">Aprobado</span>
                            @else
                                <span class="badge badge-danger">Rechazado</span>
                            @endif
                        </td>
                        <td>
                            @if($vacacion->comentario)
                                <p>{{ $vacacion->comentario }}</p>
                            @else
                                <p>No hay comentarios para esta solicitud.</p>
                            @endif
                        </td>
                        <td>
                            <!-- Botón para abrir el modal de comentario -->
                            <button class="btn btn-sm btn-warning p-2" data-bs-toggle="modal"
                                data-bs-target="#addComentarioModal{{ $vacacion->id }}" title="Comentario">
                                <i class="fas fa-comment-dots text-white text-xs"></i>
                            </button>

                            <!-- Botón para aprobar o rechazar -->
                            <button class="btn btn-sm btn-info p-2" data-toggle="modal"
                                data-target="#approveRejectModal{{ $vacacion->id }}">
                                <i class="fas fa-check-circle text-white text-xs"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal de Agregar Comentario en Vacaciones -->
                    <!-- Modal de Agregar Comentario en Vacaciones -->
                    <div class="modal fade" id="addComentarioModal{{ $vacacion->id }}" tabindex="-1"
                        aria-labelledby="addComentarioModalLabel{{ $vacacion->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addComentarioModalLabel{{ $vacacion->id }}">Agregar Comentario
                                    </h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('admin.vacaciones.addComentario', $vacacion->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="comentario{{ $vacacion->id }}">Comentario:</label>
                                            <textarea name="comentario" class="w-full p-2 border rounded"
                                                id="comentario{{ $vacacion->id }}" rows="4"
                                                placeholder="Escribe tu comentario aquí"
                                                required>{{ old('comentario') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-secondary p-2"
                                            data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-sm btn-primary p-2">Agregar Comentario</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                @endforeach
            </tbody>
        </table>
    </div>
@endsection