@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Editar Empleado</h2>

        <!-- Verificación y visualización de errores -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario para editar empleado -->
        <form method="POST" action="{{ route('empleados.update', $empleado->id) }}">
            @csrf
            @method('PUT') <!-- Indicamos que es una actualización -->

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" value="{{ old('nombre', $empleado->nombre) }}"
                        required>
                </div>
                <div class="col-md-4">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control" name="apellido"
                        value="{{ old('apellido', $empleado->apellido) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" name="telefono"
                        value="{{ old('telefono', $empleado->telefono) }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="grupo_id" class="form-label">Grupo</label>
                    <select class="form-control" name="grupo_id" required>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id }}" {{ (old('grupo_id', $empleado->grupo_id) == $grupo->id) ? 'selected' : '' }}>
                                {{ $grupo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="oficina_id" class="form-label">Oficina</label>
                    <select class="form-control" name="oficina_id" required>
                        @foreach($oficinas as $oficina)
                            <option value="{{ $oficina->id }}" {{ old('oficina_id', $empleado->oficina_id) == $oficina->id ? 'selected' : '' }}>
                                {{ $oficina->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Supervisor -->
                <div class="col-md-4">
                    <label for="supervisor_id" class="form-label">Supervisor</label>
                    <select class="form-control" name="supervisor_id">
                        <option value="">Seleccionar Supervisor</option>
                        @foreach($supervisores as $supervisor)
                            <option value="{{ $supervisor->id }}" {{ old('supervisor_id', $empleado->supervisor_id) == $supervisor->id ? 'selected' : '' }}>
                                {{ $supervisor->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Estado -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-control" name="estado" required>
                            <option value="activo" {{ old('estado', $empleado->estado) == 'activo' ? 'selected' : '' }}>Activo
                            </option>
                            <option value="inactivo" {{ old('estado', $empleado->estado) == 'inactivo' ? 'selected' : '' }}>
                                Inactivo</option>
                        </select>
                    </div>
                </div>

            </div>

            <button type="submit" class="btn btn-success">Actualizar Empleado</button>
        </form>
    </div>
@endsection