@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Solicitudes de Permisos de Empleados</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Estilo para tus permisos (sin acciones) -->
        <h4 class="mb-4">Mis Solicitudes de Permiso</h4>
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
                                <span class="badge badge-warning">Pendiente</span>
                            @elseif($permiso->estado == 'pendiente_aprobacion')
                                <span class="badge badge-primary">Pendiente de Aprobación</span>
                            @elseif($permiso->estado == 'aprobado')
                                <span class="badge badge-success">Aprobado</span>
                            @elseif($permiso->estado == 'rechazado')
                                <span class="badge badge-danger">Rechazado</span>
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
                            <!-- Aquí no mostramos botones de acción, ya que estos permisos solo los puede gestionar el admin -->
                            <span class="badge badge-info">Solo admin puede aprobar</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Paginación de los permisos --}}
        <div class="mt-4">
            {{ $permisosSupervisor->links() }}
        </div>

        <!-- Estilo para los permisos de los empleados a cargo (con acciones de aprobar o rechazar) -->
        <h4 class="mb-4">Solicitudes de Permiso de Empleados a Cargo</h4>
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
                                <span class="badge badge-warning">Pendiente</span>
                            @elseif($permiso->estado == 'pendiente_aprobacion')
                                <span class="badge badge-primary">Pendiente de Aprobación</span>
                            @elseif($permiso->estado == 'aprobado')
                                <span class="badge badge-success">Aprobado</span>
                            @elseif($permiso->estado == 'rechazado')
                                <span class="badge badge-danger">Rechazado</span>
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
                                <!-- Solo el supervisor puede pre-aprobar o rechazar -->
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
                                <span class="badge badge-info">Esperando aprobación del Admin</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $permisosEmpleados->links() }}
        </div>

        {{-- Botón para crear un nuevo permiso --}}
        <a href="{{ route('supervisor.permisos.create') }}" class="btn btn-primary">Solicitar Permiso</a>
    </div>
@endsection