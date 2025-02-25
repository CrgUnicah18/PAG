@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Crear Tipo de Contrato</h1>

        <form action="{{ route('admin.configuracion.tipos-contratos.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Tipo de Contrato</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>

            <button type="submit" class="btn btn-success">Crear</button>
            <a href="{{ route('admin.configuracion.tipos-contratos.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection