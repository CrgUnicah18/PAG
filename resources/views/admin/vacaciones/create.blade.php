@extends('layouts.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if ($errors->has('duracion_excedida'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ $errors->first('duracion_excedida') }}
        </div>
    @endif


    <!-- Contenedor flex para alinear la cartilla y el formulario -->
    <div class="flex flex-wrap lg:flex-nowrap space-x-10 items-start">

        <div class="flex flex-col items-center space-y-6 mt-8">
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


        <!-- Formulario de solicitud de vacaciones -->
        <div class="flex-1 bg-white p-8 rounded-2xl shadow-xl border border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Crear Solicitud de Vacaciones</h1>

            <form action="{{ route('admin.vacaciones.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="empleado_id" value="{{ auth()->user()->empleado_id }}">

                <!-- Fecha de Inicio -->
                <div>
                    <label for="fecha_inicio" class="block text-gray-700 font-medium mb-1">Fecha de Inicio</label>
                    <input type="text" name="fecha_inicio" id="fecha_inicio"
                        class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300" placeholder="Fecha de inicio"
                        required>
                </div>

                <!-- Fecha de Fin -->
                <div>
                    <label for="fecha_fin" class="block text-gray-700 font-medium mb-1">Fecha de Fin</label>
                    <input type="text" name="fecha_fin" id="fecha_fin"
                        class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300"
                        placeholder="Fecha de finalización" required>
                </div>

                <!-- Tipo de Vacación -->
                <div>
                    <label for="tipo_permiso_id" class="block text-gray-700 font-medium mb-1">Tipo de Vacación</label>
                    <select name="tipo_permiso_id" id="tipo_permiso_id"
                        class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300" required>
                        @foreach(\App\Models\TipoPermiso::where('es_vacacion', 1)->get() as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Comentario -->
                <div>
                    <label for="comentario" class="block text-gray-700 font-medium mb-1">Comentario (Opcional)</label>
                    <textarea name="comentario" id="comentario"
                        class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300" rows="4"></textarea>
                </div>

                <!-- Botones -->
                <div class="flex justify-between mt-4">
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Enviar
                        Solicitud</button>
                    <a href="{{ route('admin.vacaciones.index') }}"
                        class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">Cancelar</a>
                </div>

                <!-- Alertas -->
                @if(session('success'))
                    <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function deshabilitarFinesDeSemana(date) {
                return (date.getDay() === 0 || date.getDay() === 6);
            }

            // Configurar Flatpickr para Fecha de Inicio
            flatpickr("#fecha_inicio", {
                minDate: "today",
                dateFormat: "Y-m-d",
                disable: [deshabilitarFinesDeSemana]
            });

            // Configurar Flatpickr para Fecha de Fin
            flatpickr("#fecha_fin", {
                minDate: "today",
                dateFormat: "Y-m-d",
                disable: [deshabilitarFinesDeSemana]
            });
        });
    </script>




@endsection