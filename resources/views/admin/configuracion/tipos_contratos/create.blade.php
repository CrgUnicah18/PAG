@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">📜 Crear Tipo de Contrato</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.configuracion.tipos-contratos.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Tipo de Contrato</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required
                            placeholder="Ej. Contrato Indefinido">
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.configuracion.tipos-contratos.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Crear
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection