<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAG LICENCIAS Y PERMISOS</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap 5 -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery (si lo necesitas) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <style>
        a {
            text-decoration: none !important;
        }

        a:hover {
            color: inherit !important;
            /* El texto no cambia de color */
            text-decoration: none !important;
            /* Asegura que no haya subrayado */
        }

        /* Elimina el color azul al hacer clic o poner foco */
        a:focus,
        a:active {
            color: inherit !important;
            text-decoration: none !important;
        }
    </style>
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

    <script>
        function toggleModal(permisoId) {
            const modal = document.getElementById('commentModal' + permisoId);
            if (modal) {
                modal.classList.toggle('hidden');
            } else {
                console.error("No se encontró el modal para el permiso ID:", permisoId);
            }
        }
    </script>



</body>

</html>