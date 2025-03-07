@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-7xl mx-auto">
        <h2 class="text-3xl font-semibold mb-6">Crear Nueva Oficina</h2>

        <form action="{{ route('admin.configuracion.store_oficina.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre de la Oficina</label>
                <input type="text" id="nombre" name="nombre" class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                    required>
            </div>

            <div class="mb-4">
                <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección de la Oficina</label>
                <input type="text" id="direccion" name="direccion"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>

            <button type="submit" class="bg-blue-500 text-white p-2 rounded-md">Crear Oficina</button>
        </form>
    </div>
@endsection