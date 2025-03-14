@extends('layouts.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container mt-5">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6"> <!-- Cambié a grid con dos columnas -->

            <!-- Cartilla de días de vacaciones disponibles -->
            <div
                class="w-full bg-gradient-to-br from-white to-gray-100 rounded-2xl shadow-2xl border border-gray-200 p-6 transition-transform hover:scale-105 duration-300 ease-in-out">
                <!-- Encabezado -->
                <div class="bg-green-600 text-white py-3 px-4 rounded-xl shadow-md text-center mb-5">
                    <h2 class="text-xl font-bold tracking-wide">Vacaciones Restantes</h2>
                </div>

                <!-- Cuerpo de la cartilla -->
                <div class="text-center space-y-2">
                    <p class="text-gray-600 text-sm">Días disponibles para el empleado:</p>
                    <div class="text-5xl font-extrabold text-orange-500 drop-shadow-sm">
                        {{ $vacacionesRestantes }}<span class="text-2xl font-medium"> días</span>
                    </div>
                </div>

                <!-- Ícono decorativo -->
                <div class="mt-6 flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-green-500 opacity-70" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 12H4m0 0l4-4m-4 4l4 4" />
                    </svg>
                </div>
            </div>

            <!-- Formulario para crear la solicitud de vacaciones -->
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
    </div>
@endsection