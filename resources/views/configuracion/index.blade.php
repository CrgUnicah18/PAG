@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Botón para gestionar tipos de permisos -->
        <div class="bg-blue-500 p-6 rounded-lg shadow-lg text-center text-white hover:bg-blue-600">
            <a href="{{ route('configuracion.tipos-permisos.index') }}" class="text-2xl font-semibold">
                ✨ Tipos de Permisos
            </a>
        </div>

        <!-- Botón para eliminar empleados -->
        <div class="bg-red-500 p-6 rounded-lg shadow-lg text-center text-white hover:bg-red-600">
            <a href="{{ route('configuracion.eliminar-empleado.index') }}" class="text-2xl font-semibold">
                🚮 Eliminar Empleados
            </a>
        </div>
    </div>
@endsection