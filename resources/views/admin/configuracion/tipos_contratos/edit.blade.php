@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h2 class="mb-0">✏️ Editar Tipo de Contrato</h2>
            </div>
            <div class="card-body">
                <!-- Mostrar errores de validación -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('admin.configuracion.tipos-contratos.update', $tipoContrato) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Tipo de Contrato</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                            value="{{ old('nombre', $tipoContrato->nombre) }}" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.configuracion.tipos-contratos.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection