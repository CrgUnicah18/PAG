@extends('layouts.app')

@section('title', 'Editar Grupo')

@section('content')
    <div class="p-6 max-w-7xl mx-auto">
        <h2 class="text-3xl font-semibold mb-6">Editar Programa</h2>

        <form action="{{ route('admin.configuracion.update_grupo.update', $grupo->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nombre del grupo -->
            <div class="mb-4">
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre del Programa</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $grupo->nombre) }}"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                @error('nombre')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Oficina del grupo -->
            <div class="mb-4">
                <label for="oficina_id" class="block text-sm font-medium text-gray-700">Oficina</label>
                <select name="oficina_id" id="oficina_id"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    @foreach ($oficinas as $oficina)
                        <option value="{{ $oficina->id }}" {{ $grupo->oficina_id == $oficina->id ? 'selected' : '' }}>
                            {{ $oficina->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('oficina_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-between">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg shadow-md transition duration-300">
                    Actualizar
                </button>
                <a href="{{ route('admin.configuracion.crear_grupo.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg shadow-md transition duration-300">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection