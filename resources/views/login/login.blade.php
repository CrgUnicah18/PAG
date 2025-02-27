<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg w-96">
            <h2 class="text-2xl font-bold mb-4 text-center">Login</h2>

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

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-600">Correo Electrónico</label>
                    <input id="email" type="email" name="email"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('email') }}"
                        required autofocus>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-600">Contraseña</label>
                    <input id="password" type="password" name="password"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Login</button>
                </div>
            </form>

            <div class="mt-4 text-center">
                <a href="{{ route('register') }}" class="text-blue-500">¿No tienes una cuenta? Regístrate</a>
            </div>
        </div>
    </div>

</body>

</html>