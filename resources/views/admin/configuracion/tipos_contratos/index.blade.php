@extends('layouts.app')

@section('content')

    <a href="{{ route('admin.configuracion.index') }}" class="btn btn-secondary mb-3">
        ← Volver al menú de configuración
    </a>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-primary">📜 Tipos de Contrato</h1>
            <a href="{{ route('admin.configuracion.tipos-contratos.create') }}" class="btn btn-success shadow-sm">
                <i class="fas fa-plus"></i> Crear Nuevo
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tiposContratos as $tipoContrato)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $tipoContrato->nombre }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.configuracion.tipos-contratos.edit', $tipoContrato->id) }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('admin.configuracion.tipos-contratos.destroy', $tipoContrato->id) }}"
                                        method="POST" class="d-inline-block"
                                        onsubmit="return confirm('¿Seguro que quieres eliminar este contrato?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection