<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAG LICENCIAS Y PERMISOS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Estilo CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery (necesario para Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Script JS de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

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
    <?php echo $__env->make('navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Header superior -->
    <?php echo $__env->make('header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Contenido dinámico -->
    <main class="ml-64 mt-16 p-6" style="margin-top: 80px;">
        <?php echo $__env->yieldContent('content'); ?> <!-- Aquí va el contenido de cada vista -->
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

</html><?php /**PATH C:\laragon\www\PAG\resources\views/layouts/app.blade.php ENDPATH**/ ?>