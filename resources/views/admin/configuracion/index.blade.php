@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Botón para gestionar tipos de permisos -->
        <div
            class="bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 p-6 rounded-lg shadow-lg text-center text-white hover:bg-blue-600 hover:scale-105 transform transition-all duration-300 ease-in-out max-w-xs mx-auto">
            <a href="{{ route('admin.configuracion.tipos-permisos.index') }}" class="text-xl font-semibold no-underline">
                ✨ Tipos de Permisos
            </a>
        </div>

        <!-- Botón para eliminar empleados -->
        <div
            class="bg-gradient-to-r from-red-500 via-red-600 to-red-700 p-6 rounded-lg shadow-lg text-center text-white hover:bg-red-600 hover:scale-105 transform transition-all duration-300 ease-in-out max-w-xs mx-auto">
            <a href="{{ route('admin.configuracion.eliminar-empleado.index') }}" class="text-xl font-semibold no-underline">
                🚮 Terminar Empleados
            </a>
        </div>

        <!-- Botón para gestionar tipos de contratos -->
        <div
            class="bg-gradient-to-r from-green-500 via-green-600 to-green-700 p-6 rounded-lg shadow-lg text-center text-white hover:bg-green-600 hover:scale-105 transform transition-all duration-300 ease-in-out max-w-xs mx-auto">
            <a href="{{ route('admin.configuracion.tipos-contratos.index') }}" class="text-xl font-semibold no-underline">
                📄 Tipos de Contratos
            </a>
        </div>
    </div>

    <div class="p-6 max-w-7xl mx-auto mt-8">
        <h2 class="text-4xl font-bold mb-6 text-center text-gray-800">Módulos de Configuración</h2>

        <div class="mb-6">
            <!-- Enlace a la lista de oficinas -->
            <a href="{{ route('admin.configuracion.oficinas.index') }}"
                class="block bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 p-4 rounded-lg text-white text-center hover:bg-blue-500 hover:scale-105 transform transition-all duration-300 ease-in-out max-w-xs mx-auto">
                🏢 Listar Oficinas
            </a>
        </div>

        <div class="mb-6">
            <!-- Enlace para crear oficina -->
            <a href="{{ route('admin.configuracion.crear_oficina.create') }}"
                class="block bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 p-4 rounded-lg text-white text-center hover:bg-yellow-500 hover:scale-105 transform transition-all duration-300 ease-in-out max-w-xs mx-auto">
                + Crear Oficina
            </a>
        </div>

        <div class="mb-6">
            <!-- Enlace para crear un nuevo grupo -->
            <a href="{{ route('admin.configuracion.crear_grupo.create') }}"
                class="block bg-gradient-to-r from-green-400 via-green-500 to-green-600 p-4 rounded-lg text-white text-center hover:bg-green-500 hover:scale-105 transform transition-all duration-300 ease-in-out max-w-xs mx-auto">
                + Crear Nuevo Grupo
            </a>
        </div>

        <div class="mb-6">
            <!-- Enlace a la lista de grupos -->
            <a href="{{ route('admin.configuracion.crear_grupo.index') }}"
                class="block bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 p-4 rounded-lg text-white text-center hover:bg-blue-500 hover:scale-105 transform transition-all duration-300 ease-in-out max-w-xs mx-auto">
                Ver Grupos
            </a>
        </div>
    </div>
@endsection