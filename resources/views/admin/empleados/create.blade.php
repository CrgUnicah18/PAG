@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-xl font-semibold text-gray-800 shadow-sm bg-gray-100 p-4 rounded-md mb-4 text-center">
            Crear Empleado
        </h2>

        <div class="card mx-auto shadow-lg" style="max-width: 800px;">
            <div class="card-body">
                <form action="{{ route('admin.empleados.storeEmpleado') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <!-- Nombre -->
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" id="nombre"
                                placeholder="Nombre del empleado" required>
                        </div>

                        <!-- Apellido -->
                        <div class="col-md-6">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" name="apellido" class="form-control" id="apellido"
                                placeholder="Apellido del empleado" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Dirección -->
                        <div class="col-md-12">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control" id="direccion"
                                placeholder="Dirección del empleado" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Teléfono -->
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" id="telefono"
                                placeholder="Teléfono del empleado" required>
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="col-md-6">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" class="form-control" id="fecha_nacimiento" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Oficina -->
                        <div class="col-md-6">
                            <label for="oficina_id" class="form-label">Oficina</label>
                            <select name="oficina_id" class="form-select" id="oficina_id" required>
                                <option value="">Seleccionar Oficina</option>
                                @foreach($oficinas as $oficina)
                                    <option value="{{ $oficina->id }}">{{ $oficina->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Grupo -->
                        <div class="col-md-6">
                            <label for="grupo_id" class="form-label">Grupo</label>
                            <select name="grupo_id" class="form-select" id="grupo_id" required>
                                <option value="">Seleccionar Grupo</option>
                                @foreach($grupos as $grupo)
                                    <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Supervisor -->
                        <div class="col-md-6">
                            <label for="supervisor_id" class="form-label">Supervisor</label>
                            <select name="supervisor_id" class="form-select" id="supervisor_id">
                                <option value="">Seleccionar Supervisor</option>
                                @foreach($supervisores as $supervisor)
                                    <option value="{{ $supervisor->id }}">{{ $supervisor->nombre }} {{ $supervisor->apellido }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tipo de Contrato -->
                        <div class="col-md-6">
                            <label for="tipo_contrato_id" class="form-label">Tipo de Contrato</label>
                            <select name="tipo_contrato_id" class="form-select" id="tipo_contrato_id" required>
                                <option value="">Seleccionar Tipo de Contrato</option>
                                @foreach($tiposContratos as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Fecha de Ingreso -->
                        <div class="col-md-6">
                            <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                            <input type="date" name="fecha_ingreso" class="form-control" id="fecha_ingreso" required>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-6">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" name="estado" required>
                                <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Foto de Perfil -->
                        <div class="col-md-6">
                            <label for="foto_perfil" class="form-label">Foto de Perfil</label>
                            <input type="file" name="foto_perfil" class="form-control" id="foto_perfil" accept="image/*">
                        </div>

                        <!-- Contrato -->
                        <div class="col-md-6">
                            <label for="documento_contrato" class="form-label">Contrato</label>
                            <input type="file" name="documento_contrato" class="form-control" id="documento_contrato"
                                accept=".pdf, .doc, .docx">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Botón de Enviar -->
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100 py-2">Guardar Empleado</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection