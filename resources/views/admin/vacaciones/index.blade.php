@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Solicitudes de Vacaciones</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vacacionesGenerales as $vacacion)
                    <tr>
                        <td>{{ $vacacion->empleado->nombre }} {{ $vacacion->empleado->apellido }}</td>
                        <td>{{ $vacacion->fecha_inicio }}</td>
                        <td>{{ $vacacion->fecha_fin }}</td>
                        <td>{{ $vacacion->estado }}</td>
                        <td>
                            @if($vacacion->estado == 'pendiente')
                                <form action="{{ route('admin.vacaciones.aprobar', $vacacion->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Aprobar</button>
                                </form>

                                <form action="{{ route('admin.vacaciones.declinar', $vacacion->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Rechazar</button>
                                </form>

                            @elseif($vacacion->estado == 'pendientes_aprobacion')
                                <!-- Formulario para aprobar -->
                                <form action="{{ route('admin.vacaciones.aprobar', $vacacion->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Aprobar</button>
                                </form>

                                <!-- Formulario para rechazar -->
                                <form action="{{ route('admin.vacaciones.declinar', $vacacion->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Rechazar</button>
                                </form>
                            @elseif($vacacion->estado == 'aprobadas' || $vacacion->estado == 'rechazadas')
                                <span class="badge badge-secondary">{{ ucfirst($vacacion->estado) }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection