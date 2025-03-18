@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Generar Reporte de Empleados</h1>

        @if(session('error'))
            <div class="bg-red-500 text-white p-3 rounded-md mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Formulario para PDF --}}
        <form action="{{ route('admin.empleados.generarReporte') }}" method="POST" class="space-y-4" id="form_pdf">
            @csrf
            <div>
                <h3 class="text-lg font-semibold">Seleccione los campos que desea incluir en el reporte:</h3>
                <div class="grid grid-cols-2 gap-2 mt-2">
                    @foreach($campos as $campo => $label)
                        @if($campo != 'genero') <!-- Excluir el campo de género -->
                            <div class="flex items-center">
                                <input class="form-checkbox h-5 w-5 text-blue-600" type="checkbox" name="campos[]"
                                    value="{{ $campo }}" id="pdf_{{ $campo }}">
                                <label class="ml-2 text-gray-700" for="pdf_{{ $campo }}">{{ $label }}</label>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <button type="submit"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">Exportar
                a PDF</button>
        </form>

        {{-- Formulario para Excel --}}
        <form action="{{ route('admin.empleados.generarReporteExcel') }}" method="POST" class="space-y-4 mt-6"
            id="form_excel">
            @csrf
            <div>
                <h3 class="text-lg font-semibold">Seleccione los campos que desea incluir en el reporte Excel:</h3>
                <div class="grid grid-cols-2 gap-2 mt-2">
                    @foreach($campos as $campo => $label)
                        @if($campo != 'genero') <!-- Excluir el campo de género -->
                            <div class="flex items-center">
                                <input class="form-checkbox h-5 w-5 text-green-600" type="checkbox" name="campos[]"
                                    value="{{ $campo }}" id="excel_{{ $campo }}">
                                <label class="ml-2 text-gray-700" for="excel_{{ $campo }}">{{ $label }}</label>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">Exportar
                a Excel</button>
        </form>
    </div>

    {{-- Modal de alerta --}}
    <div id="alertModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h3 class="text-xl font-semibold text-center text-red-600">¡Error!</h3>
            <p class="text-center text-gray-700 mt-2">Por favor, seleccione al menos un campo para generar el reporte.</p>
            <div class="mt-4 text-center">
                <button id="closeModal" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Cerrar</button>
            </div>
        </div>
    </div>

    <script>
        // Validación de formulario antes de enviar
        document.getElementById('form_pdf').addEventListener('submit', function (event) {
            if (!hasSelectedCheckbox('pdf_')) {
                event.preventDefault(); // Previene el envío del formulario
                showModal(); // Muestra el modal de alerta
            }
        });

        document.getElementById('form_excel').addEventListener('submit', function (event) {
            if (!hasSelectedCheckbox('excel_')) {
                event.preventDefault(); // Previene el envío del formulario
                showModal(); // Muestra el modal de alerta
            }
        });

        // Función para verificar si al menos un checkbox está seleccionado
        function hasSelectedCheckbox(prefix) {
            const checkboxes = document.querySelectorAll(`input[id^="${prefix}"]`);
            return Array.from(checkboxes).some(checkbox => checkbox.checked);
        }

        // Función para mostrar el modal
        function showModal() {
            document.getElementById('alertModal').classList.remove('hidden');
        }

        // Función para cerrar el modal
        document.getElementById('closeModal').addEventListener('click', function () {
            document.getElementById('alertModal').classList.add('hidden');
        });
    </script>
@endsection