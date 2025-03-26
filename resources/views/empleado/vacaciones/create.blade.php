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

    <div class="flex flex-row justify-center items-start gap-8 mt-8">
        <!-- Sección de Cartillas (Izquierda) -->
        <div class="flex flex-col space-y-6">
            <!-- Cartilla de Vacaciones Disponibles -->
            <div
                class="w-full max-w-sm bg-gradient-to-br from-white to-gray-100 rounded-2xl shadow-2xl border border-gray-200 p-6 transition-transform hover:scale-105 duration-300 ease-in-out">
                <div class="bg-green-600 text-white py-3 px-4 rounded-xl shadow-md text-center mb-5">
                    <h2 class="text-xl font-bold tracking-wide">Vacaciones Disponibles</h2>
                </div>
                <div class="text-center space-y-2">
                    <p class="text-gray-600 text-sm">Días disponibles para el empleado:</p>
                    <div class="text-5xl font-extrabold text-orange-500 drop-shadow-sm">
                        {{ $vacacionesRestantes['vacaciones_restantes'] }}<span class="text-2xl font-medium"> días</span>
                    </div>
                </div>
                <div class="mt-6 flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-green-500 opacity-70" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 12H4m0 0l4-4m-4 4l4 4" />
                    </svg>
                </div>
            </div>

            <!-- Cartilla de Total de Días Legales -->
            <div
                class="w-full max-w-sm bg-gradient-to-br from-white to-gray-100 rounded-2xl shadow-2xl border border-gray-200 p-6 transition-transform hover:scale-105 duration-300 ease-in-out">
                <div class="bg-blue-600 text-white py-3 px-4 rounded-xl shadow-md text-center mb-5">
                    <h2 class="text-xl font-bold tracking-wide">Total de días legales</h2>
                </div>
                <div class="text-center space-y-2">
                    <p class="text-gray-600 text-sm">Días al año por el empleado:</p>
                    <div class="text-5xl font-extrabold text-blue-500 drop-shadow-sm">
                        {{ $vacacionesRestantes['vacaciones_tomadas'] }}<span class="text-2xl font-medium"> días</span>
                    </div>
                </div>
                <div class="mt-6 flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-blue-500 opacity-70" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 12H4m0 0l4-4m-4 4l4 4" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Formulario (Derecha) -->
        <div class="w-full max-w-lg bg-white p-8 rounded-2xl shadow-xl border border-gray-200">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Crear Solicitud de Vacaciones (Propia)</h1>

            <form action="{{ route('empleado.vacaciones.store') }}" method="POST">
                @csrf

                <input type="hidden" name="empleado_id" value="{{ auth()->user()->empleado_id }}">

                <!-- Fecha de Inicio -->
                <div class="mb-4">
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha de Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio"
                        class="mt-1 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <!-- Fecha de Fin -->
                <div class="mb-4">
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Fecha de Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin"
                        class="mt-1 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <!-- Tipo de Vacación -->
                <div class="mb-4">
                    <label for="tipo_permiso_id" class="block text-sm font-medium text-gray-700">Tipo de Vacación</label>
                    <select name="tipo_permiso_id" id="tipo_permiso_id"
                        class="mt-1 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                        @foreach(\App\Models\TipoPermiso::where('es_vacacion', 1)->get() as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Comentario -->
                <div class="mb-4">
                    <label for="comentario" class="block text-sm font-medium text-gray-700">Comentario (Opcional)</label>
                    <textarea name="comentario" id="comentario"
                        class="mt-1 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        rows="4"></textarea>
                </div>

                <!-- Botones de Enviar y Cancelar -->
                <div class="flex justify-between space-x-4 mt-6">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Enviar
                        Solicitud</button>
                    <a href="{{ route('empleado.vacaciones.index') }}"
                        class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">Cancelar</a>
                </div>

                <!-- Mensajes de Éxito y Error -->
                @if(session('success'))
                    <div class="mt-4 p-4 bg-green-100 text-green-700 border border-green-200 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mt-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded-md">
                        {{ session('error') }}
                    </div>
                @endif

            </form>
        </div>
    </div>
@endsection