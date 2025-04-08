@extends('layouts.app')

@section('title', 'Editar Oficina')

@section('content')
    <div class="p-6 max-w-4xl mx-auto">
        <div class="bg-white shadow-xl rounded-xl p-6">
            <h3 class="text-3xl font-bold text-gray-800 mb-6">Editar Oficina</h3>

            <form action="{{ route('admin.configuracion.update_oficina.update', $oficina->id) }}" method="POST"
                class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="nombre" class="block text-sm font-semibold text-gray-700">Nombre de la Oficina</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $oficina->nombre) }}"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none focus:border-blue-500 @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="direccion" class="block text-sm font-semibold text-gray-700">Dirección</label>
                    <input type="text" id="direccion" name="direccion" value="{{ old('direccion', $oficina->direccion) }}"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none focus:border-blue-500 @error('direccion') border-red-500 @enderror">
                    @error('direccion')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.configuracion.oficinas.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-6 py-2 rounded-lg shadow transition">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition">
                        Actualizar Oficina
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection