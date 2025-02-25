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
        <form method="POST" action="{{ route('admin.empleados.update', $empleado->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" value="{{ old('nombre', $empleado->nombre) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control" name="apellido" value="{{ old('apellido', $empleado->apellido) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" name="telefono" value="{{ old('telefono', $empleado->telefono) }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="grupo_id" class="form-label">Grupo</label>
                    <select class="form-control" name="grupo_id" required>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id }}" {{ old('grupo_id', $empleado->grupo_id) == $grupo->id ? 'selected' : '' }}>
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
            </div>

            <div class="row mb-3">
                <!-- Estado -->
                <div class="col-md-4">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-control" name="estado" required>
                        <option value="activo" {{ old('estado', $empleado->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado', $empleado->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <!-- Tipo de contrato desde la base de datos -->
                <div class="col-md-4">
                    <label for="tipo_contrato_id" class="form-label">Tipo de Contrato</label>
                    <select class="form-control" name="tipo_contrato_id" required>
                        @foreach($tiposContratos as $tipo)
                            <option value="{{ $tipo->id }}" {{ old('tipo_contrato_id', $empleado->tipo_contrato_id) == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha de ingreso -->
                <div class="col-md-4">
                    <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                    <input type="date" class="form-control" name="fecha_ingreso" value="{{ old('fecha_ingreso', $empleado->fecha_ingreso) }}" required>
                </div>
            </div>

            <div class="row mb-3">
    <!-- Imagen de perfil -->
    <div class="col-md-6">
        <label for="foto_perfil" class="form-label">Imagen de Perfil</label>
        <input type="file" class="form-control" name="foto_perfil" accept="image/*">

        @if($empleado->foto_perfil)
            <div class="mt-2">
                <!-- Ruta actualizada a public/empleados/img -->
                <img src="{{ asset('empleados/img/' . $empleado->foto_perfil) }}" alt="Perfil" width="100">
            </div>
        @endif
    </div>

    <!-- Imagen del contrato -->
    <div class="col-md-6">
        <label for="documento_contrato" class="form-label">Imagen del Contrato</label>
        <input type="file" class="form-control" name="documento_contrato" accept="image/*">

        @if($empleado->documento_contrato)
            <div class="mt-2">
                <!-- Ruta actualizada a public/empleados/img_contratos -->
                <img src="{{ asset('empleados/img_contratos/' . $empleado->documento_contrato) }}" alt="Contrato" width="100">
            </div>
        @endif
    </div>
</div>


            <button type="submit" class="btn btn-success">Actualizar Empleado</button>
        </form>
    </div>
@endsection
