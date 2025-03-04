@extends('layouts.app')

@section('content')
    <div class="container">
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
        <h4 class="text-xl font-semibold text-gray-800 mb-4 border-b-2 border-gray-300 pb-2">
            Mis solicitudes de permiso
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
                @foreach($permisosEmpleado as $permiso)
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
                            @if($permiso->estado == 'pendiente' || $permiso == 'pendiente_aprobacion')
                                <!-- Aquí no mostramos botones de acción, ya que estos permisos solo los puede gestionar el admin -->
                                <span class="badge badge-info">Su solicitud esta siendo procesada</span>
                            @else
                                <span class="badge badge-info">Solicitud procesada</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Paginación de los permisos --}}
        <div class="mt-4">
            {{ $permisosEmpleado->links() }}
        </div>

        {{-- Botón para crear un nuevo permiso --}}
        <a href="{{ route('empleado.permisos.create') }}" class="btn btn-primary">Solicitar Permiso</a>
    </div>
@endsection