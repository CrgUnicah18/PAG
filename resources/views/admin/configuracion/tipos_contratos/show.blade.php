@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Detalles del Tipo de Contrato</h1>

        <div class="mb-3">
            <strong>Nombre:</strong> {{ $tipoContrato->nombre }}
        </div>

        <a href="{{ route('admin.configuracion.tipos-contratos.index') }}" class="btn btn-secondary">Volver a la lista</a>
    </div>
@endsection