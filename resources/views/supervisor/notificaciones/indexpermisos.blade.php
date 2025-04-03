@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Permisos de Empleados a Cargo</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Tipo de Permiso</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permisos as $permiso)
                    <tr>
                        <td>{{ $permiso->empleado->nombre }}</td>
                        <td>{{ $permiso->tipo_permiso->nombre }}</td>
                        <td>{{ $permiso->fecha_inicio }}</td>
                        <td>{{ $permiso->fecha_fin }}</td>
                        <td><span class="badge bg-warning">{{ ucfirst($permiso->estado) }}</span></td>
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