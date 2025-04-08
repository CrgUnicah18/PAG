@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-4xl mx-auto">
        <div class="bg-white shadow-xl rounded-xl p-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Crear Nueva Oficina</h2>

            <form action="{{ route('admin.configuracion.store_oficina.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="nombre" class="block text-sm font-semibold text-gray-700">Nombre de la Oficina</label>
                    <input type="text" id="nombre" name="nombre"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none focus:border-blue-500"
                        required>
                </div>

                <div>
                    <label for="direccion" class="block text-sm font-semibold text-gray-700">Dirección de la Oficina</label>
                    <input type="text" id="direccion" name="direccion"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none focus:border-blue-500"
                        required>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.configuracion.oficinas.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-6 py-2 rounded-lg shadow transition">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition">
                        Guardar Oficina
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection