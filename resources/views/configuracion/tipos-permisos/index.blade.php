@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-xl font-semibold text-gray-800 shadow-sm bg-gray-100 p-3 rounded-md">
            Tipos de permisos
        </h1>




        <!-- Mensaje de éxito si existe -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-end mb-2 p-3 relative right-7">
            <a href="{{ route('configuracion.tipos-permisos.create') }}" class="btn btn-primary">
                Crear permiso
            </a>
        </div>

        <!-- Tabla de tipos de permisos -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Días</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($tiposPermiso) && $tiposPermiso->count() > 0)
                    @foreach ($tiposPermiso as $tipoPermiso)
                        <tr>
                            <td>{{ $tipoPermiso->nombre }}</td>
                            <td>{{ $tipoPermiso->descripcion }}</td>
                            <td>{{ $tipoPermiso->dias }}</td>
                            <td>
                                <a href="{{ route('configuracion.tipos-permisos.edit', $tipoPermiso->id) }}"
                                    class="btn btn-warning">Editar</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="text-center">No hay tipos de permisos registrados.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection