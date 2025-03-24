<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg w-96">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Registro de Empleado</h2>

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

            <form method="POST" action="{{ route('register.submit') }}" enctype="multipart/form-data">
                @csrf

                <!-- Datos personales del empleado -->
                <div class="mb-6">
                    <label for="nombre" class="block text-sm font-medium text-gray-600">Nombre</label>
                    <input id="nombre" type="text" name="nombre"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('nombre') }}"
                        required autofocus>
                </div>

                <div class="mb-6">
                    <label for="apellido" class="block text-sm font-medium text-gray-600">Apellido</label>
                    <input id="apellido" type="text" name="apellido"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('apellido') }}"
                        required>
                </div>
                <!-- Campo de DN -->
                <div class="mb-6">
                    <label for="dn" class="block text-sm font-medium text-gray-600">Número de Identidad (DN)</label>
                    <input id="dn" type="text" name="dn" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        value="{{ old('dn') }}" required maxlength="15">
                </div>

                <!-- Subir documento (Fotografía/PDF) -->
                <div class="mb-6">
                    <label for="dn_file" class="block text-sm font-medium text-gray-600">Subir Documento
                        (Fotografía/PDF) del DNI</label>
                    <input id="dn_file" type="file" name="dn_file"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>


                <!-- Datos generales -->
                <div class="mb-6 grid grid-cols-2 gap-4">
                    <div>
                        <label for="genero" class="block text-sm font-medium text-gray-600">Género</label>
                        <select id="genero" name="genero"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="F" {{ old('genero') == 'F' ? 'selected' : '' }}>Femenino</option>
                            <option value="M" {{ old('genero') == 'M' ? 'selected' : '' }}>Masculino</option>
                        </select>
                    </div>

                    <div>
                        <label for="fecha_ingreso" class="block text-sm font-medium text-gray-600">Fecha de
                            Ingreso</label>
                        <input id="fecha_ingreso" type="date" name="fecha_ingreso"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            value="{{ old('fecha_ingreso') }}" required>
                    </div>
                </div>

                <!-- Tipo de contrato y dirección -->
                <div class="mb-6 grid grid-cols-2 gap-4">
                    <div>
                        <label for="tipo_contrato_id" class="block text-sm font-medium text-gray-600">Tipo de
                            Contrato</label>
                        <select id="tipo_contrato_id" name="tipo_contrato_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @foreach ($tiposContrato as $tipo)
                                <option value="{{ $tipo->id }}" {{ old('tipo_contrato_id') == $tipo->id ? 'selected' : '' }}>
                                    {{ $tipo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="direccion" class="block text-sm font-medium text-gray-600">Dirección</label>
                        <input id="direccion" type="text" name="direccion"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            value="{{ old('direccion') }}" required>
                    </div>
                </div>

                <!-- Contacto y nacimiento -->
                <div class="mb-6 grid grid-cols-2 gap-4">
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-600">Teléfono</label>
                        <input id="telefono" type="text" name="telefono"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('telefono') }}"
                            required>
                    </div>

                    <div>
                        <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-600">Fecha de
                            Nacimiento</label>
                        <input id="fecha_nacimiento" type="date" name="fecha_nacimiento"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            value="{{ old('fecha_nacimiento') }}" required>
                    </div>
                </div>

                <!-- Oficina, grupo y foto de perfil -->
                <div class="mb-6 grid grid-cols-2 gap-4">
                    <div>
                        <label for="oficina_id" class="block text-sm font-medium text-gray-600">Oficina</label>
                        <select id="oficina_id" name="oficina_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach ($oficinas as $oficina)
                                <option value="{{ $oficina->id }}" {{ old('oficina_id') == $oficina->id ? 'selected' : '' }}>
                                    {{ $oficina->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="grupo_id" class="block text-sm font-medium text-gray-600">Grupo</label>
                        <select id="grupo_id" name="grupo_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach ($grupos as $grupo)
                                <option value="{{ $grupo->id }}" {{ old('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                    {{ $grupo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Foto de perfil -->
                <div class="mb-6">
                    <label for="foto_perfil" class="block text-sm font-medium text-gray-600">Foto de Perfil</label>
                    <input id="foto_perfil" type="file" name="foto_perfil"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <!-- Botón de envío -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md">Registrar
                        Empleado</button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700">¿Ya tienes cuenta? Inicia
                    sesión</a>
            </div>
        </div>
    </div>

</body>

</html>