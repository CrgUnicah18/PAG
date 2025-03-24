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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery (si lo necesitas) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Incluye Heroicons desde CDN si no estás usando Laravel Mix o Vite -->
    <script src="https://unpkg.com/feather-icons"></script>

    <script src="https://unpkg.com/lucide@latest"></script>

    <script src="//unpkg.com/alpinejs" defer></script>




    <!-- Agregar en tu archivo Blade (resources/views/layouts/app.blade.php) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <style>
        .comment-container {
            max-height: 120px;
            /* Mantén esta propiedad para que puedas hacer scroll */
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 5px;
            border-radius: 5px;
            font-size: 12px;
            line-height: 1.0;
            /* Ajusta la altura de línea para que no esté tan espaciado */
            white-space: pre-wrap;
            background-color: #f9f9f9;
        }

        .comment-line {
            margin-bottom: 0.5px;
            /* Reduce el espacio entre comentarios */
        }

        .comment-container p {
            word-wrap: break-word;
            /* Evita que el texto largo se desborde */
            margin: 0;
            /* Elimina márgenes adicionales de los párrafos */
            padding: 0;
            /* Elimina padding extra dentro de los párrafos */
        }


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

        /* Contenedor del switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 28px;
        }

        /* Estilo del switch (fondo) */
        .switch input {
            opacity: 0;
            /* Ocultar el input real */
            width: 0;
            height: 0;
        }

        /* Estilo del fondo del switch */
        .switch .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 50px;
        }

        /* Estilo de la palanca (circle) */
        .switch .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            border-radius: 50px;
            background-color: white;
            transition: 0.4s;
            left: 4px;
            bottom: 4px;
        }

        /* Cuando el switch está activado (checked) */
        .switch input:checked+.slider {
            background-color: #007aff;
            /* Azul similar al de iPhone */
        }

        /* Movimiento de la palanca cuando el switch está activado */
        .switch input:checked+.slider:before {
            transform: translateX(22px);
        }
    </style>

</head>

<body class="bg-[rgb(232,236,237)]">

    <!-- Navbar lateral -->
    @include('navbar') <!-- Aquí se incluye el navbar -->

    <!-- Header superior -->
    @include('header') <!-- Aquí se incluye el header -->

    <!-- Contenido dinámico -->
    <main class="bg-[rgb(232,236,237)] lg:ml-64 mt-4 lg:mt-16 p-6">
        @yield('content')
    </main>


    <script>
        // Toggle sidebar en pantallas pequeñas
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('hidden');
        });

        // Toggle Dropdown de usuario
        document.getElementById('userMenuButton').addEventListener('click', function () {
            document.getElementById('userDropdown').classList.toggle('hidden');
        });

        // Cierra el menú si haces clic fuera de él
        document.addEventListener('click', function (event) {
            let dropdown = document.getElementById('userDropdown');
            let button = document.getElementById('userMenuButton');
            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

    <!-- Activar íconos de Feather al cargar -->
    <script>
        feather.replace()
    </script>
    {{-- Script para íconos Lucide --}}

</body>

</html>