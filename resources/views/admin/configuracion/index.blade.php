@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-7xl mx-auto">
        <h2 class="text-4xl font-bold mb-10 text-center text-gray-800">Módulos de Configuración</h2>

        {{-- Sección superior: botones principales en una grilla responsiva --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <!-- Tipos de Permisos -->
            <a href="{{ route('admin.configuracion.tipos-permisos.index') }}"
                class="bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 p-6 rounded-lg shadow-lg text-center text-white hover:bg-blue-600 hover:scale-105 transform transition-all duration-300 ease-in-out">
                ✨ <span class="text-xl font-semibold">Tipos de Permisos</span>
            </a>

            <!-- Terminar Empleados -->
            <a href="{{ route('admin.configuracion.eliminar-empleado.index') }}"
                class="bg-gradient-to-r from-red-500 via-red-600 to-red-700 p-6 rounded-lg shadow-lg text-center text-white hover:bg-red-600 hover:scale-105 transform transition-all duration-300 ease-in-out">
                🚮 <span class="text-xl font-semibold">Terminar Empleados</span>
            </a>

            <!-- Tipos de Contratos -->
            <a href="{{ route('admin.configuracion.tipos-contratos.index') }}"
                class="bg-gradient-to-r from-green-500 via-green-600 to-green-700 p-6 rounded-lg shadow-lg text-center text-white hover:bg-green-600 hover:scale-105 transform transition-all duration-300 ease-in-out">
                📄 <span class="text-xl font-semibold">Tipos de Contratos</span>
            </a>
        </div>

        {{-- Sección inferior: gestión de oficinas y grupos --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            <!-- Lista de Oficinas -->
            <a href="{{ route('admin.configuracion.oficinas.index') }}"
                class="bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 p-4 rounded-lg text-white text-center hover:bg-blue-500 hover:scale-105 transform transition-all duration-300 ease-in-out">
                🏢 <span class="text-lg font-medium">Listar Oficinas</span>
            </a>

            <!-- Crear Oficina -->
            <a href="{{ route('admin.configuracion.crear_oficina.create') }}"
                class="bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 p-4 rounded-lg text-white text-center hover:bg-yellow-500 hover:scale-105 transform transition-all duration-300 ease-in-out">
                ➕ <span class="text-lg font-medium">Crear Oficina</span>
            </a>

            <!-- Crear Grupo -->
            <a href="{{ route('admin.configuracion.crear_grupo.create') }}"
                class="bg-gradient-to-r from-green-400 via-green-500 to-green-600 p-4 rounded-lg text-white text-center hover:bg-green-500 hover:scale-105 transform transition-all duration-300 ease-in-out">
                ➕ <span class="text-lg font-medium">Crear Programa</span>
            </a>

            <!-- Ver Grupos -->
            <a href="{{ route('admin.configuracion.crear_grupo.index') }}"
                class="bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 p-4 rounded-lg text-white text-center hover:bg-blue-500 hover:scale-105 transform transition-all duration-300 ease-in-out">
                👥 <span class="text-lg font-medium">Ver Programas</span>
            </a>
        </div>
    </div>
@endsection