<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permisos y Vacaciones - PAG</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Fondo degradado */
        .bg-gradient {
            background: linear-gradient(135deg, rgb(221, 176, 43), rgb(194, 194, 20), rgb(40, 214, 28));
        }
    </style>
</head>

<body class="bg-gradient min-h-screen flex items-center justify-center">

    <div class="bg-white p-10 rounded-xl shadow-xl max-w-md w-full">

        <!-- Título -->
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Bienvenido de nuevo</h2>

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
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input id="email" type="email" name="email"
                    class="mt-2 block w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('email') }}" required autofocus>
            </div>

            <!-- Campo de Contraseña -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input id="password" type="password" name="password"
                    class="mt-2 block w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Botón de login -->
            <div class="flex justify-center">
                <button type="submit"
                    class="w-full bg-purple-500 hover:bg-blue-600 text-white py-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
                    Iniciar sesión
                </button>
            </div>
        </form>

    </div>

</body>

</html>