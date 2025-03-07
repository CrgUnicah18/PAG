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

    <div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Crear Solicitud de Vacaciones (Propia)</h1>

        <form action="{{ route('supervisor.vacaciones.store') }}" method="POST">
            @csrf

            <input type="hidden" name="empleado_id" value="{{ auth()->user()->empleado_id }}">

            <!-- Fecha de Inicio -->
            <div class="mb-4">
                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha de Inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio"
                    class="mt-1 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Fecha de Fin -->
            <div class="mb-4">
                <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Fecha de Fin</label>
                <input type="date" name="fecha_fin" id="fecha_fin"
                    class="mt-1 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Tipo de Vacación -->
            <div class="mb-4">
                <label for="tipo_permiso_id" class="block text-sm font-medium text-gray-700">Tipo de Vacación</label>
                <select name="tipo_permiso_id" id="tipo_permiso_id"
                    class="mt-1 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    @foreach(\App\Models\TipoPermiso::where('es_vacacion', 1)->get() as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Comentario -->
            <div class="mb-4">
                <label for="comentario" class="block text-sm font-medium text-gray-700">Comentario (Opcional)</label>
                <textarea name="comentario" id="comentario"
                    class="mt-1 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="4"></textarea>
            </div>

            <!-- Botones de Enviar y Cancelar -->
            <div class="flex justify-end space-x-4 mt-6">
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Enviar
                    Solicitud</button>
                <a href="{{ route('supervisor.vacaciones.index') }}"
                    class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">Cancelar</a>
            </div>

            <!-- Mensajes de Éxito y Error -->
            @if(session('success'))
                <div class="mt-4 p-4 bg-green-100 text-green-700 border border-green-200 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mt-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

        </form>
    </div>
@endsection