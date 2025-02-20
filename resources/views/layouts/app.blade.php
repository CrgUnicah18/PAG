<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAG LICENCIAS Y PERMISOS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <!-- Navbar lateral -->
    @include('navbar')

    <!-- Header superior -->
    @include('header')

    <!-- Contenido dinámico -->
    <main class="ml-64 mt-16 p-6" style="margin-top: 80px;">
        @yield('content') <!-- Aquí va el contenido de cada vista -->
    </main>


</body>

</html>