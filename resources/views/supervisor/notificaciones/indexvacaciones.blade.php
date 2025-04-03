@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Vacaciones de Empleados a Cargo</h2>
        <table class="table table-striped">
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
                @foreach ($vacaciones as $vacacion)
                    <tr>
                        <td>{{ $vacacion->empleado->nombre }}</td>
                        <td>{{ $vacacion->fecha_inicio }}</td>
                        <td>{{ $vacacion->fecha_fin }}</td>
                        <td><span class="badge bg-warning">{{ ucfirst($vacacion->estado) }}</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary">Ver</a>
                            <a href="#" class="btn btn-sm btn-success">Aprobar</a>
                            <a href="#" class="btn btn-sm btn-danger">Rechazar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection