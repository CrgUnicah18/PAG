<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAG LICENCIAS Y PERMISOS</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <!-- Agregar FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    @vite('resources/js/app.js')

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

<body>

    <div class="container">
        @yield('content')
    </div>






</body>

<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jQuery (si lo necesitas) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</html>