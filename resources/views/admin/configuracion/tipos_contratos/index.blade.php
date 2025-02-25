@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tipos de Contrato</h1>
        <a href="{{ route('admin.configuracion.tipos-contratos.create') }}" class="btn btn-primary mb-3">Crear Nuevo Tipo de
            Contrato</a>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tiposContratos as $tipoContrato)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $tipoContrato->nombre }}</td>
                        <td>
                            <a href="{{ route('admin.configuracion.tipos-contratos.edit', $tipoContrato->id) }}"
                                class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('admin.configuracion.tipos-contratos.destroy', $tipoContrato->id) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection