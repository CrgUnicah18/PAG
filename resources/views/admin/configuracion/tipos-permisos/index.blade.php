@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h2 class="mb-0">📝 Tipos de Permisos</h2>
                <a href="{{ route('admin.configuracion.tipos-permisos.create') }}" class="btn btn-light">
                    <i class="fas fa-plus"></i> Crear Permiso
                </a>
            </div>
            <div class="card-body">
                <!-- Mensaje de éxito -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Tabla de tipos de permisos -->
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Días</th>
                                <th>¿Es Vacación?</th>
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
                                        <td>{!! $tipoPermiso->es_vacacion ? '<span class="text-success">✅ Sí</span>' : '<span class="text-danger">❌ No</span>' !!}</td>
                                        <td>
                                            <a href="{{ route('admin.configuracion.tipos-permisos.edit', $tipoPermiso->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hay tipos de permisos registrados.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
