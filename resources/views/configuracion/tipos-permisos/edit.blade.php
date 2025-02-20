@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Editar Duración del Permiso</h1>

        <!-- Mostrar mensajes de éxito -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif


        <!-- Formulario para editar la duración -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <!-- Aquí usamos la ruta update de los recursos con el método PUT -->
            <form action="{{ route('configuracion.tipos-permisos.update', $tipoPermiso->id) }}" method="POST">
                @csrf
                @method('PATCH') <!-- Aquí usas PATCH para la actualización -->

                <!-- Nombre del permiso -->
                <div class="mb-4">
                    <label for="nombre" class="block text-lg font-medium text-gray-700">Nombre del Tipo de Permiso:</label>
                    <input type="text" name="nombre" id="nombre" class="mt-2 block w-full p-3 border rounded"
                        value="{{ old('nombre', $tipoPermiso->nombre) }}" required>
                </div>

                <!-- Descripción del permiso -->
                <div class="mb-4">
                    <label for="descripcion" class="block text-lg font-medium text-gray-700">Descripción:</label>
                    <textarea name="descripcion" id="descripcion"
                        class="mt-2 block w-full p-3 border rounded">{{ old('descripcion', $tipoPermiso->descripcion) }}</textarea>
                </div>

                <!-- Duración del permiso -->
                <div class="mb-4">
                    <label for="dias">Días</label>
                    <input type="number" name="dias" value="{{ old('dias', $tipoPermiso->dias) }}">

                </div>

                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">Guardar
                    Cambios</button>
            </form>
        </div>
    </div>
@endsection