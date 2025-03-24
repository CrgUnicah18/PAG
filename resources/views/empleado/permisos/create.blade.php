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
                                                    <option value="{{ $tipo->id }}" data-requiere-subsidio="{{ $tipo->requiere_subsidio }}">
                                                        {{ $tipo->nombre }}
                                                    </option>
                                    @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="fecha_inicio" class="text-lg font-medium text-gray-700">Fecha de Inicio</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" min="{{ \Carbon\Carbon::today()->toDateString() }}"
                    class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                <div id="fecha_inicio_error" class="text-red-500 text-sm mt-1 hidden">
                    La fecha de inicio no puede ser menor a hoy.
                </div>
            </div>

            <div class="form-group">
                <label for="fecha_fin" class="text-lg font-medium text-gray-700">Fecha de Fin</label>
                <input type="date" id="fecha_fin" name="fecha_fin" min="{{ \Carbon\Carbon::today()->toDateString() }}"
                    class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                <div id="fecha_fin_error" class="text-red-500 text-sm mt-1 hidden">
                    La fecha de fin debe ser igual o posterior a la de inicio.
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
                <label for="subsidio_archivo" class="text-lg font-medium text-gray-700">Archivo de Subsidio (PDF o
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
            const subsidioArchivoDiv = document.getElementById('subsidio_archivo_div');
            const subsidioInput = document.getElementById('subsidio'); // Asegúrate de tener el ID correcto para el campo de subsidio

            // Verifica el tipo de permiso seleccionado y muestra el campo de archivo de subsidio si corresponde
            tipoPermisoSelect.addEventListener('change', function () {
                const selectedOption = tipoPermisoSelect.options[tipoPermisoSelect.selectedIndex];
                const requiereSubsidio = selectedOption.getAttribute('data-requiere-subsidio');

                if (requiereSubsidio == 1) {
                    subsidioArchivoDiv.style.display = 'block'; // Muestra el campo de archivo
                    subsidioInput.removeAttribute('required'); // Siempre lo hace opcional, incluso cuando se muestre
                } else {
                    subsidioArchivoDiv.style.display = 'none'; // Oculta el campo de archivo
                    subsidioInput.removeAttribute('required'); // Siempre lo hace opcional, incluso cuando se oculte
                }
            });
        });
    </script>

@endsection