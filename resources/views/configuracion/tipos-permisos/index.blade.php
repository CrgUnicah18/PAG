@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tipos de Permisos</h1>

        <!-- Mensaje de éxito si existe -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Botón para crear un nuevo tipo de permiso -->
        <a href="{{ route('configuracion.tipos-permisos.create') }}" class="btn btn-primary mb-3">Crear Nuevo Tipo de
            Permiso</a>

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

                                <form action="{{ route('configuracion.tipos-permisos.destroy', $tipoPermiso->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
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