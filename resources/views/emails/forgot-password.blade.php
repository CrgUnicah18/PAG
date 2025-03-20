<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olvidaste tu Contraseña</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="http://pag.test/images/logopag2.png" alt="Logo de la empresa" class="h-16">
        </div>

        <!-- Título -->
        <h2 class="text-2xl font-semibold text-center text-gray-700 mb-4">Recuperación de Contraseña</h2>

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

        <!-- Formulario de Olvido de Contraseña -->
        <form method="POST" action="{{ route('password.sendPin') }}">
            @csrf

            <!-- Campo de Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-600">Correo Electrónico</label>
                <input id="email" type="email" name="email"
                    class="mt-2 block w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('email') }}" required autofocus>
            </div>

            <!-- Botón de Enviar PIN -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-md transition duration-200">
                Enviar PIN
            </button>

        </form>
    </div>
</body>

</html>