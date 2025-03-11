@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-12 px-4">
        <!-- Título con tamaño reducido -->
        <h2 class="text-2xl font-semibold text-center mb-6">Crear Usuario para Empleado</h2>

        <!-- Formulario en Card, más amplio -->
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <form action="{{ route('admin.storeUsuario', ['empleado_id' => $empleado_id]) }}" method="POST">
                @csrf
                <input type="hidden" name="empleado_id" value="{{ $empleado_id }}">

                <!-- Campo de Nombre de Usuario -->
                <div class="mb-6">
                    <label for="name" class="block text-gray-700 font-medium">Nombre de Usuario</label>
                    <input type="text" name="name"
                        class="w-full mt-2 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" id="name"
                        value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Campo de Correo Electrónico -->
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 font-medium">Correo Electrónico</label>
                    <input type="email" name="email"
                        class="w-full mt-2 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                        id="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Campo de Contraseña -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-medium">Contraseña</label>
                    <input type="password" name="password"
                        class="w-full mt-2 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                        id="password" required>
                    @error('password')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Campo para Confirmar Contraseña -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 font-medium">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation"
                        class="w-full mt-2 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                        id="password_confirmation" required>
                    @error('password_confirmation')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Selección del Rol -->
                <div class="mb-6">
                    <label for="rol" class="block text-gray-700 font-medium">Rol del Usuario</label>
                    <select name="rol" id="rol"
                        class="w-full mt-2 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
                        <option value="empleado">Empleado</option>
                        <option value="supervisor">Supervisor</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>

                <!-- Contenedor de botones -->
                <div class="flex justify-between mt-6">
                    <!-- Botón de Enviar -->
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Crear Usuario
                    </button>

                    <!-- Botón de Cancelar -->
                    <a href="{{ route('admin.empleados.index') }}"
                        class="px-6 py-3 bg-gray-400 text-white rounded-md hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection