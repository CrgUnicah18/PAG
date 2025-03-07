@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h1 class="text-center mb-4">Crear Solicitud de Vacaciones</h1>

            <form action="{{ route('empleado.vacaciones.store') }}" method="POST">
                @csrf
                <input type="hidden" name="empleado_id" value="{{ auth()->user()->empleado_id }}">

                <div class="form-group mb-3">
                    <label for="fecha_inicio">Fecha de Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label for="fecha_fin">Fecha de Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                </div>

                <!-- Campo para seleccionar el tipo de vacación -->
                <div class="form-group mb-3">
                    <label for="tipo_permiso_id">Tipo de Vacación</label>
                    <select name="tipo_permiso_id" id="tipo_permiso_id" class="form-control" required>
                        @foreach(\App\Models\TipoPermiso::where('es_vacacion', 1)->get() as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option> <!-- Mostramos el nombre -->
                        @endforeach
                    </select>
                </div>

                <!-- Campo para agregar comentario -->
                <div class="form-group mb-3">
                    <label for="comentario">Comentario (Opcional)</label>
                    <textarea name="comentario" id="comentario" class="form-control" rows="4"></textarea>
                </div>

                <div class="form-group mt-4 d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary btn-lg">Enviar Solicitud</button>
                    <a href="{{ route('empleado.vacaciones.index') }}" class="btn btn-secondary btn-lg">Cancelar</a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger mt-3">
                        {{ session('error') }}
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection