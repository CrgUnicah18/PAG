@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-7xl mx-auto">
        <h2 class="text-3xl font-semibold mb-6">Crear Nuevo Grupo</h2>

        <form action="{{ route('admin.configuracion.store_grupo.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre del Grupo</label>
                <input type="text" id="nombre" name="nombre" class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                    required>
            </div>

            <div class="mb-4">
                <label for="oficina_id" class="block text-sm font-medium text-gray-700">Oficina</label>
                <select name="oficina_id" id="oficina_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                    required>
                    @foreach ($oficinas as $oficina)
                        <option value="{{ $oficina->id }}">{{ $oficina->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-blue-500 text-white p-2 rounded-md">Crear Grupo</button>
        </form>
    </div>
@endsection