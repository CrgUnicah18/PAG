@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        <!-- Estilo para tus permisos (sin acciones) -->
        <h4 class="text-xl font-semibold text-gray-800 mb-4 border-b-2 border-gray-300 pb-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="mb-3">
                    <h2 class="text-xl font-semibold text-gray-800">📝 Solicitud de Permisos</h2>
                </div>

                {{-- Botón para crear un nuevo permiso --}}
                <a href="{{ route('supervisor.permisos.create') }}" class="btn btn-primary btn-sm">Solicitar Permiso</a>
            </div>
        </h4>

        @if(session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabla de permisos del supervisor -->
        <div class="table-responsive">
            <h4 class="text-xl font-semibold text-gray-800 mt-8 mb-4 border-b-2 border-gray-300 pb-2">
                Permisos del Supervisor
            </h4>
            <table class="table table-striped table-hover table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Empleado</th>
                        <th>Tipo de Permiso</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                        <th>Estado</th>
                        <th>Comentario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permisosSupervisor as $permiso)
                        <tr>
                            <td>{{ $permiso->empleado->nombre }} {{ $permiso->empleado->apellido }}</td>
                            <td>{{ $permiso->tipoPermiso->nombre }}</td>
                            <td>{{ \Carbon\Carbon::parse($permiso->fecha_inicio)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($permiso->fecha_fin)->format('d/m/Y') }}</td>
                            <td>
                                @if($permiso->estado == 'pendiente')
                                    <span class="badge bg-warning">Pendiente</span>
                                @elseif($permiso->estado == 'pendiente_aprobacion')
                                    <span class="badge bg-primary">Pendiente de Aprobación</span>
                                @elseif($permiso->estado == 'aprobado')
                                    <span class="badge bg-success">Aprobado</span>
                                @elseif($permiso->estado == 'rechazado')
                                    <span class="badge bg-danger">Rechazado</span>
                                @endif
                            </td>
                            <td>
                                @if($permiso->comentario)
                                    {{ $permiso->comentario }}
                                @else
                                    <span class="text-gray-400">No hay comentario</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">Solo admin puede aprobar</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginación de los permisos del supervisor -->
            <div class="mt-4">
                {{ $permisosSupervisor->links() }}
            </div>
        </div>

        <!-- Estilo para los permisos de los empleados a cargo (con acciones de aprobar o rechazar) -->
        <h4 class="text-xl font-semibold text-gray-800 mt-8 mb-4 border-b-2 border-gray-300 pb-2">
            Permisos de mis empleados
        </h4>

        @if($permisosEmpleados->isEmpty())
            <p class="text-center text-gray-500">No tienes empleados bajo tu supervisión o no hay permisos solicitados.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Empleado</th>
                            <th>Tipo de Permiso</th>
                            <th>Fecha de Inicio</th>
                            <th>Fecha de Fin</th>
                            <th>Comentario</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permisosEmpleados as $permiso)
                            <tr>
                                <td>{{ $permiso->empleado->nombre }} {{ $permiso->empleado->apellido }}</td>
                                <td>{{ $permiso->tipoPermiso->nombre }}</td>
                                <td>{{ \Carbon\Carbon::parse($permiso->fecha_inicio)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($permiso->fecha_fin)->format('d/m/Y') }}</td>
                                <td>
                                    @if($permiso->estado == 'pendiente')
                                        <span class="badge bg-warning">Pendiente</span>
                                    @elseif($permiso->estado == 'pendiente_aprobacion')
                                        <span class="badge bg-primary">Pendiente de Aprobación</span>
                                    @elseif($permiso->estado == 'aprobado')
                                        <span class="badge bg-success">Aprobado</span>
                                    @elseif($permiso->estado == 'rechazado')
                                        <span class="badge bg-danger">Rechazado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($permiso->comentario)
                                        {{ $permiso->comentario }}
                                    @else
                                        <span class="text-gray-400">No hay comentario</span>
                                    @endif
                                </td>
                                <td>
                                    @if($permiso->estado == 'pendiente')
                                        <!-- Botones de acción para aprobar o rechazar permisos -->
                                        <form action="{{ route('supervisor.permisos.aprobar', $permiso->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Pre-Aprobar</button>
                                        </form>
                                        <form action="{{ route('supervisor.permisos.declinar', $permiso->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Rechazar</button>
                                        </form>
                                    @elseif($permiso->estado == 'pendiente_aprobacion')
                                        <span class="badge bg-info">Esperando aprobación del Admin</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Paginación de los permisos de los empleados -->
                <div class="mt-4">
                    {{ $permisosEmpleados->links() }}
                </div>
            </div>
        @endif

    </div>
@endsection