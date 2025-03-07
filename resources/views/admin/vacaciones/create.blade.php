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

    <div class="container mx-auto max-w-lg bg-white p-8 rounded-lg shadow-lg mt-10">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Crear Solicitud de Vacaciones</h1>

        <form action="{{ route('admin.vacaciones.store') }}" method="POST" class="space-y-4">
            @csrf

            <input type="hidden" name="empleado_id" value="{{ auth()->user()->empleado_id }}">

            <!-- Fecha de Inicio -->
            <div>
                <label for="fecha_inicio" class="block text-gray-700 font-medium mb-1">Fecha de Inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio"
                    class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300" required>
            </div>

            <!-- Fecha de Fin -->
            <div>
                <label for="fecha_fin" class="block text-gray-700 font-medium mb-1">Fecha de Fin</label>
                <input type="date" name="fecha_fin" id="fecha_fin"
                    class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300" required>
            </div>

            <!-- Tipo de Vacación -->
            <div>
                <label for="tipo_permiso_id" class="block text-gray-700 font-medium mb-1">Tipo de Vacación</label>
                <select name="tipo_permiso_id" id="tipo_permiso_id"
                    class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300" required>
                    @foreach(\App\Models\TipoPermiso::where('es_vacacion', 1)->get() as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Comentario -->
            <div>
                <label for="comentario" class="block text-gray-700 font-medium mb-1">Comentario (Opcional)</label>
                <textarea name="comentario" id="comentario"
                    class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300" rows="4"></textarea>
            </div>

            <!-- Botones -->
            <div class="flex justify-between mt-4">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Enviar
                    Solicitud</button>
                <a href="{{ route('admin.vacaciones.index') }}"
                    class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">Cancelar</a>
            </div>

            <!-- Alertas -->
            @if(session('success'))
                <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif
        </form>
    </div>
@endsection