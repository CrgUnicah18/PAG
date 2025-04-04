@extends('layouts.app')

@section('content')
    @if ($errors->any())
        <script>
            // Mostrar una alerta si hay errores
            alert("{{ $errors->first() }}");
        </script>
    @endif
    <div class="container mt-4">
        <h2 class="text-xl font-semibold text-gray-800 shadow-sm bg-gray-100 p-4 rounded-md mb-4 text-center">
            Crear Empleado
        </h2>

        <div class="card mx-auto shadow-lg" style="max-width: 800px;">
            <div class="card-body">
                <form action="{{ route('admin.empleados.storeEmpleado') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @php
                        $empleado = $empleado ?? new \App\Models\Empleado();
                    @endphp

                    <div class="row p-2 shadow m-3">
                        <!-- Nombre -->
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre <span class="text-red-500">*</span></label>
                            <input type="text" name="nombre"
                                class="form-control border-2 border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500"
                                id="nombre" placeholder="Nombre del empleado" required
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <!-- Cargo -->
                        <div class="col-md-6">
                            <label for="cargo" class="form-label">Cargo <span class="text-red-500">*</span></label>
                            <input type="text" name="cargo"
                                class="form-control border-2 border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500"
                                id="cargo" placeholder="Cargo del empleado" required
                                oninput="this.value = this.value.toUpperCase()">
                        </div>

                        <!-- Campo de DN -->
                        <div class="col-md-6">
                            <label for="dn" class="form-label">Número de Identidad (DNI) <span
                                    class="text-red-500">*</span></label>
                            <input type="text"
                                class="form-control border-2 border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500"
                                id="dn" name="dn" required maxlength="15" placeholder="xxxx-xxxx-xxxxx"
                                oninput="formatDN(this)">
                        </div>

                        <script>
                            function formatDN(input) {
                                // Remover cualquier carácter no numérico
                                let value = input.value.replace(/\D/g, '');

                                // Agregar guiones en el formato xxxx-xxxx-xxxxx
                                if (value.length > 8) {
                                    value = value.replace(/(\d{4})(\d{4})(\d{0,3})/, '$1-$2-$3');
                                } else if (value.length > 4) {
                                    value = value.replace(/(\d{4})(\d{0,4})/, '$1-$2');
                                }

                                // Asignar el valor con guiones
                                input.value = value;
                            }
                        </script>

                        <!-- Apellido -->
                        <div class="col-md-6">
                            <label for="apellido" class="form-label">Apellido <span class="text-red-500">*</span></label>
                            <input type="text" name="apellido"
                                class="form-control border-2 border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500"
                                id="apellido" placeholder="Apellido del empleado" required
                                oninput="this.value = this.value.toUpperCase()">
                        </div>

                        <!-- Campo para subir archivo (fotografía o PDF) -->
                        <div class="col-md-6">
                            <label for="dn_file" class="form-label p-1">DNI</label>
                            <input type="file" class="form-control" id="dn_file" name="dn_file">
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
                            <label for="telefono" class="form-label">Teléfono <span class="text-red-500">*</span></label>
                            <input type="text" name="telefono"
                                class="form-control border-2 border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500"
                                id="telefono" placeholder="Teléfono del empleado" required>
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="col-md-6">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="fecha_nacimiento"
                                class="form-control border-2 border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500"
                                id="fecha_nacimiento" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Oficina -->
                        <div class="col-md-6">
                            <label for="oficina_id" class="form-label">Oficina <span class="text-red-500">*</span></label>
                            <select name="oficina_id"
                                class="form-select border-2 border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500"
                                id="oficina_id" required>
                                <option value="">Seleccionar Oficina</option>
                                @foreach($oficinas as $oficina)
                                    <option value="{{ $oficina->id }}">{{ $oficina->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Grupo -->
                        <div class="col-md-6">
                            <label for="grupo_id" class="form-label">Programa <span class="text-red-500">*</span></label>
                            <select name="grupo_id"
                                class="form-select border-2 border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500"
                                id="grupo_id" required>
                                <option value="">Seleccionar programa</option>
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
                            <label for="tipo_contrato_id" class="form-label">Tipo de Contrato <span
                                    class="text-red-500">*</span></label>
                            <select name="tipo_contrato_id"
                                class="form-select border-2 border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500"
                                id="tipo_contrato_id" required>
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
                            <label for="fecha_ingreso" class="form-label">Fecha de Ingreso <span
                                    class="text-red-500">*</span></label>
                            <input type="date"
                                class="form-control border-2 border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500"
                                id="fecha_ingreso" name="fecha_ingreso" required>
                            @error('fecha_ingreso')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Estado -->
                        <div class="col-md-6">
                            <label for="estado" class="form-label">Estado <span class="text-red-500">*</span></label>
                            <select
                                class="form-select border-2 border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500"
                                name="estado" required>
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

                    <div class="col-md-6 m-2">
                        <label for="genero" class="form-label">Género <span class="text-red-500">*</span></label>
                        <select name="genero" id="genero"
                            class="form-control border-2 border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500"
                            required>
                            <option value="M" {{ old('genero', $empleado->genero) == 'M' ? 'selected' : '' }}>Masculino
                            </option>
                            <option value="F" {{ old('genero', $empleado->genero) == 'F' ? 'selected' : '' }}>Femenino
                            </option>
                        </select>
                    </div>

                    <div class="flex justify-center gap-4 mt-6">
                        <!-- Botón de Enviar -->
                        <button type="submit" id="btn-submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md inline-flex items-center transition-all duration-300">
                            <svg id="spinner" class="animate-spin h-5 w-5 mr-2 hidden" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                            </svg>
                            <span id="btn-text">Crear empleado</span>
                        </button>

                        <!-- Botón Cancelar -->
                        <a href="{{ route('admin.empleados.index') }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg shadow-md transition-all duration-300">
                            Cancelar
                        </a>
                    </div>

                    <script>
                        const form = document.querySelector('form');
                        const btn = document.getElementById('btn-submit');
                        const spinner = document.getElementById('spinner');
                        const btnText = document.getElementById('btn-text');

                        form.addEventListener('submit', function () {
                            btn.disabled = true;
                            spinner.classList.remove('hidden');
                            btnText.textContent = 'Guardando...';
                        });
                    </script>

                </form>
            </div>
        </div>
    </div>
@endsection