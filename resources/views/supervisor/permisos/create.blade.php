@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Solicitar Permiso</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('supervisor.permisos.store') }}" method="POST">
            @csrf

            <!-- No es necesario que el supervisor seleccione su propio nombre, ya que el usuario logueado es él -->
            <input type="hidden" name="empleado_id" value="{{ auth()->user()->id }}">

            <div class="form-group">
                <label for="tipo_permiso_id">Tipo de Permiso</label>
                <select name="tipo_permiso_id" id="tipo_permiso_id" class="form-control" required>
                    <option value="">Selecciona el tipo de permiso</option>
                    @foreach($tiposPermiso as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="fecha_inicio">Fecha de Inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="fecha_fin">Fecha de Fin</label>
                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="comentario">Comentario</label>
                <textarea name="comentario" id="comentario" class="form-control" rows="3"
                    placeholder="Detalles del permiso..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Solicitar Permiso</button>
        </form>
    </div>
@endsection