<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - PAG</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100">
    <!-- Mensaje de éxito -->
    @if (session('status'))
        <div class="absolute top-16 w-full bg-green-500 text-white p-4 rounded-lg mb-4">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="http://pag.test/images/logopag2.png" alt="Logo de la empresa" class="h-16">
        </div>

        <!-- Título -->
        <h2 class="text-2xl font-semibold text-center text-gray-700 mb-4">Acceso a la plataforma</h2>

        <!-- Mostrar errores -->
        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario de Login -->
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <!-- Campo de Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-600">Correo Electrónico</label>
                <input id="email" type="email" name="email"
                    class="mt-2 block w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('email') }}" required autofocus>
            </div>

            <!-- Campo de Contraseña -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-600">Contraseña</label>
                <input id="password" type="password" name="password"
                    class="mt-2 block w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Opciones -->
            <div class="flex justify-between items-center mb-6">
                <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox text-blue-500">
                    <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                </label>

                <!-- Enlace a registro de empleado a la derecha -->
                <a href="{{ route('register') }}" class="text-sm text-blue-500 hover:text-blue-700">¿No tienes cuenta?
                    Registrate</a>
            </div>

            <!-- Botón de login -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-md transition duration-200">
                Iniciar sesión
            </button>

            <!-- Enlace para restablecer la contraseña -->
            <div class="text-center mt-4">
                <a href="{{ route('password.forgot') }}" class="text-sm text-blue-500 hover:text-blue-700">¿Olvidaste
                    tu contraseña?</a>
            </div>
        </form>
    </div>

</body>

</html>