@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Solicitudes de Vacaciones</h1>

        <!-- Vacaciones propias del Supervisor -->
        <h2>Mis Solicitudes de Vacaciones</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Duración (días)</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vacacionesPropias as $vacacion)
                    <tr>
                        <td>{{ $vacacion->empleado->nombre }}</td>
                        <td>{{ $vacacion->fecha_inicio }}</td>
                        <td>{{ $vacacion->fecha_fin }}</td>
                        <td>{{ $vacacion->duracion_dias }}</td>
                        <td>{{ $vacacion->estado }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Vacaciones de los empleados asignados -->
        <h2>Solicitudes de Vacaciones de mis Empleados</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Duración (días)</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vacacionesGenerales as $vacacion)
                    <tr>
                        <td>{{ $vacacion->empleado->nombre }}</td>
                        <td>{{ $vacacion->fecha_inicio }}</td>
                        <td>{{ $vacacion->fecha_fin }}</td>
                        <td>{{ $vacacion->duracion_dias }}</td>
                        <td>{{ $vacacion->estado }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection