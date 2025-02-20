@extends('layouts.app')

@section('content')
    <div class="container mt-2"> <!-- Reducimos el margen superior a mt-4 para que se acerque más al navbar -->
        <!-- Título del formulario -->
        <h2 class="text-xl justify-center font-semibold text-gray-800 shadow-sm bg-gray-100 p-3 rounded-md mb-2">Crear
            Empleado</h2>

        <!-- Formulario en una card -->
        <div class="card" style="max-width: 800px; margin: 0 auto;"> <!-- Limitar el ancho de la carta -->
            <div class="card-body">
                <form action="{{ route('empleados.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- Nombre -->
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" id="nombre"
                                placeholder="Nombre del empleado" required>
                        </div>

                        <!-- Apellido -->
                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" name="apellido" class="form-control" id="apellido"
                                placeholder="Apellido del empleado" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Dirección -->
                        <div class="col-md-12 mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control" id="direccion"
                                placeholder="Dirección del empleado" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Teléfono -->
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" id="telefono"
                                placeholder="Teléfono del empleado" required>
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="col-md-6 mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" class="form-control" id="fecha_nacimiento" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Oficina -->
                        <div class="col-md-6 mb-3">
                            <label for="oficina_id" class="form-label">Oficina</label>
                            <select name="oficina_id" class="form-select" id="oficina_id" required>
                                <option value="">Seleccionar Oficina</option>
                                @foreach($oficinas as $oficina)
                                    <option value="{{ $oficina->id }}">{{ $oficina->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Grupo -->
                        <div class="col-md-6 mb-3">
                            <label for="grupo_id" class="form-label">Grupo</label>
                            <select name="grupo_id" class="form-select" id="grupo_id" required>
                                <option value="">Seleccionar Grupo</option>
                                @foreach($grupos as $grupo)
                                    <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Supervisor -->
                        <div class="col-md-6 mb-3">
                            <label for="supervisor_id" class="form-label">Supervisor</label>
                            <select name="supervisor_id" class="form-select" id="supervisor_id">
                                <option value="">Seleccionar Supervisor</option>
                                @foreach($supervisores as $supervisor)
                                    <option value="{{ $supervisor->id }}">{{ $supervisor->nombre }} {{ $supervisor->apellido }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Estado -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-control" name="estado" required>
                                <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>


                    <div class="row">
                        <!-- Botón de Enviar -->
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100">Guardar Empleado</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection