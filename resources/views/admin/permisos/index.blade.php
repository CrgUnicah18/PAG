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
                            <a href="{{ route('admin.permisos.edit', $permiso) }}" class="btn btn-sm btn-warning p-2"
                                title="Comentario">
                                <i class="fas fa-edit text-white text-xs"></i>
                            </a>

                            @if($permiso->estado === 'pendiente')
                                <!-- Botón para abrir el modal -->
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
                                    <!-- Botones para aprobar o rechazar -->
                                    <form action="{{ route('admin.permisos.aprobar', $permiso->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-success">Aprobar</button>
                                    </form>

                                    <form action="{{ route('admin.permisos.declinar', $permiso->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('POST')
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
@endsection