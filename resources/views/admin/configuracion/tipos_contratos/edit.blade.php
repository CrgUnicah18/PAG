@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Tipo de Contrato</h1>

        <!-- Mostrar errores de validación -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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

            <button type="submit" class="btn btn-warning">Actualizar</button>
            <a href="{{ route('admin.configuracion.tipos-contratos.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>


    </div>
@endsection