@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        <!-- Estilo para las vacaciones propias -->
        <h4 class="text-xl font-semibold text-gray-800 mb-4 border-b-2 border-gray-300 pb-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="mb-3">
                    <h2 class="text-xl font-semibold text-gray-800">🌴 Solicitud de Vacaciones</h2>
                </div>

                {{-- Botón para crear una nueva solicitud de vacaciones --}}
                <a href="{{ route('empleado.vacaciones.create') }}" class="btn btn-primary btn-sm">Solicitar Vacaciones</a>
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

        <!-- Tabla de vacaciones propias -->
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Empleado</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                        <th>Estado</th>
                        <th>Comentario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vacacionesPropias as $vacacion)
                        <tr>
                            <td>{{ $vacacion->empleado->nombre }} {{ $vacacion->empleado->apellido }}</td>
                            <td>{{ \Carbon\Carbon::parse($vacacion->fecha_inicio)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($vacacion->fecha_fin)->format('d/m/Y') }}</td>
                            <td>
                                @if($vacacion->estado == 'pendientes_aprobacion')
                                    <span class="badge bg-warning">Pendientes de Aprobación</span>
                                @elseif($vacacion->estado == 'aprobadas')
                                    <span class="badge bg-success">Aprobadas</span>
                                @elseif($vacacion->estado == 'rechazadas')
                                    <span class="badge bg-danger">Rechazadas</span>
                                @elseif($vacacion->estado == 'pendiente')
                                    <span class="badge bg-primary">Pendiente</span>
                                @endif
                            </td>
                            <td>
                                @if($vacacion->comentario)
                                    {{ $vacacion->comentario }}
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
        </div>

        <!-- Tabla de vacaciones generales (empleados a cargo) -->
        <h4 class="text-xl font-semibold text-gray-800 mt-8 mb-4 border-b-2 border-gray-300 pb-2">
            Vacaciones de mis empleados
        </h4>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Empleado</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                        <th>Estado</th>
                        <th>Comentario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vacacionesGenerales as $vacacion)
                        <tr>
                            <td>{{ $vacacion->empleado->nombre }} {{ $vacacion->empleado->apellido }}</td>
                            <td>{{ \Carbon\Carbon::parse($vacacion->fecha_inicio)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($vacacion->fecha_fin)->format('d/m/Y') }}</td>
                            <td>
                                @if($vacacion->estado == 'pendientes_aprobacion')
                                    <span class="badge bg-primary">Pendientes de Aprobación</span>
                                @elseif($vacacion->estado == 'aprobadas')
                                    <span class="badge bg-success">Aprobadas</span>
                                @elseif($vacacion->estado == 'rechazadas')
                                    <span class="badge bg-danger">Rechazadas</span>
                                @elseif($vacacion->estado == 'pendiente')
                                    <span class="badge bg-warning">Pendiente</span>
                                @endif
                            </td>
                            <td>
                                @if($vacacion->comentario)
                                    {{ $vacacion->comentario }}
                                @else
                                    <span class="text-gray-400">No hay comentario</span>
                                @endif
                            </td>
                            <td>
                                @if($vacacion->estado == 'pendiente')
                                    <!-- Botones de acción para aprobar o rechazar vacaciones -->
                                    <form action="{{ route('supervisor.vacaciones.aprobar', $vacacion->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Pre-Aprobar</button>
                                    </form>
                                    <form action="{{ route('supervisor.vacaciones.declinar', $vacacion->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Rechazar</button>
                                    </form>
                                @elseif($vacacion->estado == 'pendientes_aprobacion')
                                    <span class="badge bg-info">Esperando aprobación del Admin</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection