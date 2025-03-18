@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Crear Anuncio</h1>

        <form action="{{ route('admin.anuncios.store') }}" method="POST">
            @csrf

            <!-- Título -->
            <div class="mb-4">
                <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
                <input type="text" name="titulo" id="titulo"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    required>
            </div>

            <!-- Contenido -->
            <div class="mb-4">
                <label for="contenido" class="block text-sm font-medium text-gray-700">Contenido</label>
                <textarea name="contenido" id="contenido" rows="4"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    required></textarea>
            </div>

            <!-- Audiencia -->
            <div class="mb-4">
                <label for="audiencia" class="block text-sm font-medium text-gray-700">Audiencia</label>
                <select name="audiencia" id="audiencia"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    required>
                    <option value="empresa">Empresa</option>
                    <option value="todos">Todos</option>
                    <option value="oficina">Oficina</option>
                    <option value="grupo">Grupo</option>
                </select>
            </div>

            <!-- Secciones Dinámicas para Oficinas y Grupos -->
            <div id="dynamic-selections" class="mb-4 hidden">
                <!-- Grupos -->
                <div id="grupos-section" class="mb-4 hidden">
                    <label for="grupos" class="block text-sm font-medium text-gray-700">Grupos</label>
                    <div id="grupos-list" class="space-y-2">
                        @foreach ($grupos as $grupo)
                            <div class="flex items-center">
                                <input type="checkbox" name="grupos[]" value="{{ $grupo->id }}" id="grupo-{{ $grupo->id }}"
                                    class="mr-2">
                                <label for="grupo-{{ $grupo->id }}" class="text-sm">{{ $grupo->nombre }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Oficinas -->
                <div id="oficinas-section" class="mb-4 hidden">
                    <label for="oficinas" class="block text-sm font-medium text-gray-700">Oficinas</label>
                    <div id="oficinas-list" class="space-y-2">
                        @foreach ($oficinas as $oficina)
                            <div class="flex items-center">
                                <input type="checkbox" name="oficinas[]" value="{{ $oficina->id }}"
                                    id="oficina-{{ $oficina->id }}" class="mr-2">
                                <label for="oficina-{{ $oficina->id }}" class="text-sm">{{ $oficina->nombre }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Fecha y Hora -->
            <div class="mb-4">
                <label for="fecha_hora" class="block text-sm font-medium text-gray-700">Fecha y Hora</label>
                <input type="datetime-local" name="fecha_hora" id="fecha_hora"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Prioridad -->
            <div class="mb-6">
                <label for="prioridad" class="block text-sm font-medium text-gray-700">Prioridad</label>
                <select name="prioridad" id="prioridad"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="alta">Alta</option>
                    <option value="media" selected>Media</option>
                    <option value="baja">Baja</option>
                </select>
            </div>

            <!-- Botón -->
            <button type="submit"
                class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Crear Anuncio
            </button>
        </form>
    </div>

    <script>
        // JavaScript para manejar las selecciones dinámicas
        document.getElementById('audiencia').addEventListener('change', function () {
            const audiencia = this.value;
            const gruposSection = document.getElementById('grupos-section');
            const oficinasSection = document.getElementById('oficinas-section');
            const dynamicSelections = document.getElementById('dynamic-selections');

            // Limpiar todas las selecciones previas
            gruposSection.classList.add('hidden');
            oficinasSection.classList.add('hidden');

            // Mostrar las secciones según la opción seleccionada
            if (audiencia === 'grupo' || audiencia === 'todos') {
                gruposSection.classList.remove('hidden');
            }

            if (audiencia === 'oficina' || audiencia === 'todos') {
                oficinasSection.classList.remove('hidden');
            }

            dynamicSelections.classList.remove('hidden');
        });

        // Doble clic para agregar al listado de seleccionados
        document.querySelectorAll('#oficinas-list .flex, #grupos-list .flex').forEach(item => {
            item.addEventListener('dblclick', function () {
                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
            });
        });
    </script>
@endsection