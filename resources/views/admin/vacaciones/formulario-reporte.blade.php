@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-2xl max-w-3xl">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">📄 Generar Reporte de Vacaciones</h2>

        <form action="{{ route('admin.vacaciones.generar-reporte') }}" method="GET" class="space-y-6">
            @csrf

            <!-- Empleado -->
            <div>
                <label for="empleado_id" class="block text-gray-700 font-semibold mb-1">👤 Empleado</label>
                <select name="empleado_id" id="empleado_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">Todos</option>
                    @foreach ($empleados as $empleado)
                        <option value="{{ $empleado->id }}" {{ old('empleado_id', request('empleado_id')) == $empleado->id ? 'selected' : '' }}>
                            {{ $empleado->nombre . ' ' . $empleado->apellido }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Fechas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="fecha_inicio" class="block text-gray-700 font-semibold mb-1">📅 Fecha de inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        value="{{ old('fecha_inicio', request('fecha_inicio')) }}">
                </div>

                <div>
                    <label for="fecha_fin" class="block text-gray-700 font-semibold mb-1">📅 Fecha de fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        value="{{ old('fecha_fin', request('fecha_fin')) }}">
                </div>
            </div>

            <!-- Estado -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">🟡 Estado</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @php
                        $estados = [
                            'pendiente' => 'Pendiente',
                            'pendientes_aprobacion' => 'Pendiente Aprobación',
                            'aprobadas' => 'Aprobada',
                            'rechazadas' => 'Rechazada'
                        ];
                    @endphp

                    @foreach ($estados as $valor => $label)
                        <div class="flex items-center justify-between">
                            <label for="estado_{{ $valor }}" class="text-gray-600">{{ $label }}</label>
                            <input type="checkbox" name="estado[]" value="{{ $valor }}" id="estado_{{ $valor }}"
                                class="toggle-switch" {{ in_array($valor, old('estado', [])) ? 'checked' : '' }}>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Campos seleccionables -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">📋 Campos a mostrar</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @php
                        $campos = [
                            'empleado_id' => 'Empleado',
                            'fecha_inicio' => 'Fecha de inicio',
                            'fecha_fin' => 'Fecha de fin',
                            'duracion_dias' => 'Duración de días',
                            'tipo_permiso_id' => 'Tipo de permiso',
                            'comentario' => 'Comentario',
                            'vacaciones_restantes' => 'Vacaciones restantes',
                            'periodo' => 'Periodo',
                        ];
                    @endphp

                    @foreach ($campos as $valor => $label)
                        <div class="flex items-center justify-between">
                            <label for="{{ $valor }}_checkbox" class="text-gray-600">{{ $label }}</label>
                            <input type="checkbox" name="campos[]" value="{{ $valor }}" id="{{ $valor }}_checkbox"
                                class="toggle-switch" {{ in_array($valor, old('campos', [])) ? 'checked' : '' }}>
                        </div>
                    @endforeach

                    <!-- Estado siempre oculto y seleccionado -->
                    <input type="checkbox" name="campos[]" value="estado" checked hidden>
                </div>
            </div>

            <!-- Botón de enviar -->
            <div class="pt-4">
                <button type="submit"
                    class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md transition-all duration-200">
                    📊 Generar reporte
                </button>
            </div>
        </form>
    </div>

    <!-- Script para deshabilitar el checkbox de vacaciones_restantes -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const empleadoSelect = document.getElementById('empleado_id');
            const vacacionesRestantesCheckbox = document.getElementById('vacaciones_restantes_checkbox');

            // Función para habilitar/deshabilitar el checkbox
            function toggleVacacionesRestantes() {
                if (empleadoSelect.value === '') { // Si "Todos" está seleccionado
                    vacacionesRestantesCheckbox.checked = false; // Desmarcar el checkbox
                    vacacionesRestantesCheckbox.disabled = true; // Deshabilitar el checkbox
                } else {
                    vacacionesRestantesCheckbox.disabled = false; // Habilitar el checkbox
                }
            }

            // Llamar a la función al cargar la página
            toggleVacacionesRestantes();

            // Escuchar cambios en el select de empleado
            empleadoSelect.addEventListener('change', toggleVacacionesRestantes);
        });
    </script>
@endsection