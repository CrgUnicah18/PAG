<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <!-- Agregar una referencia a Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans flex items-center justify-center min-h-screen p-6">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full sm:w-96">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-4">Restablecer Contraseña</h2>
        <p class="text-center text-gray-600 mb-6">Para restablecer tu contraseña, ingresa el PIN que se te ha enviado
            por correo y tu nueva contraseña.</p>

        <!-- Formulario para ingresar el PIN y la nueva contraseña -->
        <form action="{{ route('password.reset.submit') }}" method="POST">
            @csrf

            <!-- Campo para el PIN -->
            <div class="mb-4">
                <label for="pin" class="block text-sm font-medium text-gray-700">PIN</label>
                <input type="text" name="pin" id="pin" required
                    class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Campo para la nueva contraseña -->
            <div class="mb-4">
                <label for="new_password" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                <input type="password" name="new_password" id="new_password" required
                    class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Campo para confirmar la nueva contraseña -->
            <div class="mb-6">
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Nueva
                    Contraseña</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                    class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit"
                class="w-full py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300">
                Restablecer Contraseña
            </button>
        </form>
    </div>

</body>

</html>