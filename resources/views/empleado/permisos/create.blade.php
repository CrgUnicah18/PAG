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

    <div class="container max-w-3xl mx-auto p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-3xl font-semibold text-center mb-6 text-gray-800">Solicitar permiso</h2>

        <!-- Mensaje de éxito o error -->
        @if(session('success'))
            <div class="alert alert-success mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('empleado.permisos.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="empleado_id" value="{{ auth()->user()->id }}">

            <div class="form-group">
                <label for="tipo_permiso_id" class="text-lg font-medium text-gray-700">Tipo de Permiso</label>
                <select name="tipo_permiso_id" id="tipo_permiso_id"
                    class="form-control w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="">Selecciona el tipo de permiso</option>
                    @foreach($tiposPermiso as $tipo)
                                    <!-- Se muestra el permiso solo si corresponde al género -->
                                    @if(
                                        (auth()->user()->genero === 'F' && $tipo->es_licencia == 1) ||
                                        (auth()->user()->genero === 'M' && $tipo->es_licenciam == 1) ||
                                        ($tipo->es_licencia == 0 && $tipo->es_licenciam == 0)
                                    )
                                                    <option value="{{ $tipo->id }}" data-requiere-subsidio="{{ $tipo->requiere_subsidio }} "
                                                        data-duracion="{{ $tipo->dias }}">
                                                        {{ $tipo->nombre }}
                                                    </option>
                                    @endif
                    @endforeach
                </select>
            </div>

            <!-- Calendario de selección de rango de fechas -->
            <div class="form-group">
                <label for="fecha_inicio" class="text-lg font-medium text-gray-700">Fecha de Inicio</label>
                <input type="text" id="fecha_inicio" name="fecha_inicio"
                    class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Selecciona la fecha de inicio" required>

                <label for="fecha_fin" class="text-lg font-medium text-gray-700 mt-4">Fecha de Fin</label>
                <input type="text" id="fecha_fin" name="fecha_fin"
                    class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Selecciona la fecha de fin" required readonly>

                <!-- Campo oculto para enviar fecha_fin aunque esté bloqueado -->
                <input type="hidden" name="fecha_fin_hidden" id="fecha_fin_hidden" value="">

                <div id="fecha_rango_error" class="text-red-500 text-sm mt-1 hidden">
                    El rango de fechas no es válido.
                </div>
            </div>

            <div class="form-group">
                <label for="comentario" class="text-lg font-medium text-gray-700">Comentario</label>
                <textarea name="comentario" id="comentario"
                    class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="4" placeholder="Detalles del permiso..."></textarea>
            </div>

            <!-- Subsidio archivo: este campo se habilita solo si el tipo de permiso requiere subsidio -->
            <div class="form-group" id="subsidio_archivo_div" style="display: none;">
                <label for="subsidio_archivo" class="text-lg font-medium text-gray-700">Archivo de Refrendamiento (PDF o
                    Imagen)</label>
                <input type="file" name="subsidio_archivo" id="subsidio_archivo"
                    class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    accept="application/pdf, image/*">
            </div>

            <button type="submit"
                class="w-full py-3 px-4 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
                style="background-color: rgb(35, 94, 167);">
                Solicitar Permiso
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tipoPermisoSelect = document.getElementById('tipo_permiso_id');
            const fechaInicioInput = document.getElementById('fecha_inicio');
            const fechaFinInput = document.getElementById('fecha_fin');
            const fechaFinHiddenInput = document.getElementById('fecha_fin_hidden');
            const subsidioArchivoDiv = document.getElementById('subsidio_archivo_div');
            const subsidioInput = document.getElementById('subsidio_archivo');
            const form = document.querySelector('form');

            // Verifica el tipo de permiso seleccionado y muestra el campo de archivo de subsidio si corresponde
            tipoPermisoSelect.addEventListener('change', function () {
                const selectedOption = tipoPermisoSelect.options[tipoPermisoSelect.selectedIndex];
                const requiereSubsidio = selectedOption.getAttribute('data-requiere-subsidio');
                const duracionPermiso = selectedOption.getAttribute('data-duracion');

                if (requiereSubsidio == 1) {
                    subsidioArchivoDiv.style.display = 'block'; // Muestra el campo de archivo
                } else {
                    subsidioArchivoDiv.style.display = 'none'; // Oculta el campo de archivo
                }

                // Si el tipo de permiso es de 1 día, asignamos el mismo valor a fecha_fin
                if (duracionPermiso == 1) {
                    fechaFinInput.value = fechaInicioInput.value;
                    fechaFinInput.disabled = true; // Bloqueamos el campo de fecha_fin
                } else {
                    fechaFinInput.disabled = false; // Habilitamos el campo de fecha_fin
                }
            });
            // Actualizar fecha_fin automáticamente cuando se elija fecha_inicio
            fechaInicioInput.addEventListener('change', function () {
                const duracionPermiso = tipoPermisoSelect.options[tipoPermisoSelect.selectedIndex].getAttribute('data-duracion');

                if (duracionPermiso == 1) {
                    fechaFinInput.value = fechaInicioInput.value; // Actualizamos fecha_fin con fecha_inicio
                    fechaFinHiddenInput.value = fechaInicioInput.value; // Sincronizamos con el campo oculto
                }
            });

            // Sincronizar fecha_fin con fecha_fin_hidden antes de enviar el formulario
            form.addEventListener('submit', function () {
                fechaFinHiddenInput.value = fechaFinInput.value;
            });

            // Inicializar flatpickr para los dos campos de fechas
            flatpickr("#fecha_inicio", {
                minDate: "today", // La fecha mínima es hoy
                dateFormat: "Y-m-d", // Formato de fecha
                locale: {
                    weekdays: {
                        shorthand: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
                    }
                },
                disable: [
                    function (date) {
                        // Deshabilita sábados (6) y domingos (0) de todos los meses
                        return (date.getDay() === 0 || date.getDay() === 6);
                    }
                ]
            });

            flatpickr("#fecha_fin", {
                minDate: "today", // La fecha mínima es hoy
                dateFormat: "Y-m-d", // Formato de fecha
                locale: {
                    weekdays: {
                        shorthand: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
                    }
                },
                disable: [
                    function (date) {
                        // Deshabilita sábados (6) y domingos (0) de todos los meses
                        return (date.getDay() === 0 || date.getDay() === 6);
                    }
                ]
            });
        });
    </script>
@endsection