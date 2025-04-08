@extends('layouts.blank')

@section('title', 'Crear Usuario')

@section('content')
    <div class="container py-5">
        <div class="max-w-lg mx-auto bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-center text-3xl font-bold text-gray-800 mb-6">Crear Usuario para el Empleado</h2>

            <!-- Formulario para registrar el usuario -->
            <form method="POST" action="{{ route('usuario.store', $empleado->id) }}">
                @csrf

                <!-- Campo para el nombre del usuario -->
                <div class="form-group mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre de Usuario</label>
                    <input type="text"
                        class="form-control mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        id="name" name="name" autocomplete="username" required>
                </div>

                <!-- Campo para el correo electrónico -->
                <div class="form-group mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email"
                        class="form-control mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        id="email" name="email" autocomplete="email" required>
                </div>

                <!-- Campo para la contraseña -->
                <div class="form-group mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password"
                        class="form-control mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        id="password" name="password" autocomplete="new-password" required>
                </div>

                <!-- Campo para confirmar la contraseña -->
                <div class="form-group mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar
                        Contraseña</label>
                    <input type="password"
                        class="form-control mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
                </div>

                <!-- Campo oculto para el ID del empleado -->
                <input type="hidden" name="empleado_id" value="{{ $empleado->id }}">

                <!-- Botón de enviar -->
                <div class="flex justify-center">
                    <button type="submit"
                        class="btn btn-primary px-6 py-3 rounded-full text-white bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-400 hover:to-teal-500 transform transition-all duration-300 focus:outline-none">
                        Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection