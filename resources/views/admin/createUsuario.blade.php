@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Crear Usuario para Empleado</h2>

        <!-- Formulario para crear el usuario -->
        <form action="{{ route('admin.storeUsuario', ['empleado_id' => $empleado_id]) }}" method="POST">
            @csrf

            <!-- Campo oculto para el empleado_id -->
            <input type="hidden" name="empleado_id" value="{{ $empleado_id }}">

            <!-- Campo para el nombre completo -->
            <div class="mb-3">
                <label for="name" class="form-label">Nombre Completo</label>
                <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Campo para el correo electrónico -->
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Campo para la contraseña -->
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" id="password" required>
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Campo para confirmar la contraseña -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation"
                    required>
                @error('password_confirmation')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="rol">Rol:</label>
                <select name="rol" id="rol" required>
                    <option value="empleado">Empleado</option>
                    <option value="supervisor">Supervisor</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>


            <!-- Botón de enviar -->
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
        </form>
    </div>
@endsection