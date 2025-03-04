@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Mis Solicitudes de Vacaciones</h1>

        <!-- Botón para Solicitar Vacaciones -->
        <a href="{{ route('empleado.vacaciones.create') }}" class="btn btn-primary mb-3">Solicitar Vacaciones</a>

        <table class="table">
            <thead>
                <tr>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Duración (días)</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vacacionesPropias as $vacacion)
                    <tr>
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