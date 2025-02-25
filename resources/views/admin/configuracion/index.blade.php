@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Botón para gestionar tipos de permisos -->
        <div
            class="bg-blue-500 p-6 rounded-lg shadow-lg text-center text-white hover:border-none hover:text-white hover:bg-blue-600 hover:no-underline">
            <a href="{{ route('admin.configuracion.tipos-permisos.index') }}" class="text-2xl font-semibold no-underline">
                ✨ Tipos de Permisos
            </a>
        </div>

        <!-- Botón para eliminar empleados -->
        <div
            class="bg-red-500 p-6 rounded-lg shadow-lg text-center text-white hover:border-none hover:text-white hover:bg-red-600 hover:no-underline">
            <a href="{{ route('admin.configuracion.eliminar-empleado.index') }}"
                class="text-2xl font-semibold no-underline">
                🚮 Terminar Empleados
            </a>
        </div>

        <!-- Botón para gestionar tipos de contratos -->
        <div
            class="bg-green-500 p-6 rounded-lg shadow-lg text-center text-white hover:border-none hover:text-white hover:bg-green-600 hover:no-underline">
            <a href="{{ route('admin.configuracion.tipos-contratos.index') }}" class="text-2xl font-semibold no-underline">
                📄 Tipos de Contratos
            </a>
        </div>
    </div>
@endsection