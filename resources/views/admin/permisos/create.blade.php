@extends('layouts.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
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

        <form action="{{ route('admin.permisos.store') }}" method="POST" class="space-y-6">
            @csrf

            <input type="hidden" name="empleado_id" value="{{ auth()->user()->id }}">

            <div class="form-group">
                <label for="tipo_permiso_id" class="text-lg font-medium text-gray-700">Tipo de Permiso</label>
                <select name="tipo_permiso_id" id="tipo_permiso_id"
                    class="form-control w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="">Selecciona el tipo de permiso</option>
                    @foreach($tiposPermiso as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
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

            {{-- Dentro del form --}}
            <div id="campo-subsidio" style="display: none;">
                <label for="subsidio_archivo">Subsidio (constancia médica):</label>
                <input type="file" name="subsidio_archivo" accept="application/pdf,image/*">
            </div>


            <button type="submit"
                class="w-full py-3 px-4 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                Solicitar Permiso
            </button>
        </form>
    </div>

    <script>
        document.querySelector("form").addEventListener("submit", function (event) {
            let valid = true;
            const fechaInicio = document.getElementById("fecha_inicio");
            const fechaFin = document.getElementById("fecha_fin");

            // Validar que la fecha de inicio no sea anterior a hoy
            if (new Date(fechaInicio.value) < new Date()) {
                document.getElementById("fecha_inicio_error").classList.remove("hidden");
                valid = false;
            } else {
                document.getElementById("fecha_inicio_error").classList.add("hidden");
            }

            // Validar que la fecha de fin no sea anterior a la fecha de inicio
            if (new Date(fechaFin.value) < new Date(fechaInicio.value)) {
                document.getElementById("fecha_fin_error").classList.remove("hidden");
                valid = false;
            } else {
                document.getElementById("fecha_fin_error").classList.add("hidden");
            }

            // Validación adicional (días de permiso) puede ser manejada desde el backend, 
            // ya que se calcula a nivel servidor si se excede el máximo de días.

            if (!valid) {
                event.preventDefault();
            }
        });

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tipoPermisoSelect = document.querySelector('select[name="tipo_permiso_id"]');
            const subsidioDiv = document.getElementById('campo-subsidio');

            tipoPermisoSelect.addEventListener('change', function () {
                const tipoId = this.value;

                fetch(`/api/tipo-permiso/${tipoId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.requiere_subsidio) {
                            subsidioDiv.style.display = 'block';
                        } else {
                            subsidioDiv.style.display = 'none';
                        }
                    });
            });

            tipoPermisoSelect.dispatchEvent(new Event('change'));
        });
    </script>

@endsection