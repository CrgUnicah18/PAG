<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAG LICENCIAS Y PERMISOS</title>

    <!-- Bootstrap 5 -->
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Agregar el CSS de Flatpickr -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

    <!-- Agregar en tu archivo Blade (resources/views/layouts/app.blade.php) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- Incluir fuente de Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">




    <style>
        body {
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            color: #333;
        }

        .comment-container {
            max-height: 120px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 5px;
            border-radius: 5px;
            font-size: 12px;
            line-height: 1.0;
            white-space: pre-wrap;
            background-color: #f9f9f9;
        }

        .comment-line {
            margin-bottom: 0.5px;
        }

        .comment-container p {
            word-wrap: break-word;
            margin: 0;
            padding: 0;
        }

        aside p,
        aside h3 {
            text-decoration: none !important;
            color: inherit !important;
        }

        aside a {
            color: white !important;
            text-decoration: none !important;
            /* Asegúrate de que este estilo esté presente */
        }

        aside a:hover {
            color: white !important;
            text-decoration: none !important;
        }

        aside a:focus,
        aside a:active {
            color: white !important;
            text-decoration: none !important;
        }


        .notify {
            position: fixed;
            top: 20px;
            right: 5px;
            width: 300px;
            padding: 35px;
            margin: 10px;
            border-radius: 8px;
            z-index: 9999;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: white;
            opacity: 1 !important;
            transition: opacity 3s ease;
        }

        /* Estilo de notificación de éxito */
        .notify-success {
            background-color: #4CAF50;
            color: white;
        }

        /* Estilo de notificación de error */
        .notify-error {
            background-color: #F44336;
            color: white;
        }

        #dropdownNotificaciones {
            position: absolute;
            top: 100%;
            /* Esto asegura que el dropdown siempre se despliegue hacia abajo */
        }

        .toggle-switch {
            appearance: none;
            width: 2.5rem;
            height: 1.4rem;
            background: #d1d5db;
            border-radius: 9999px;
            position: relative;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .toggle-switch:checked {
            background: #3b82f6;
        }

        .toggle-switch::before {
            content: "";
            position: absolute;
            width: 1.1rem;
            height: 1.1rem;
            background: white;
            border-radius: 50%;
            top: 0.15rem;
            left: 0.2rem;
            transition: transform 0.2s;
        }

        .toggle-switch:checked::before {
            transform: translateX(1.1rem);
        }

        /* Estilo para el switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 28px;
            margin-right: 10px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        .slider::before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #38b2ac;
            /* Color teal (Tailwind: teal-500) */
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #38b2ac;
        }

        input:checked+.slider::before {
            transform: translateX(22px);
        }

        /* Estilo para el slider redondo */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round::before {
            border-radius: 50%;
        }
    </style>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-[rgb(232,236,237)]">

    <!-- Navbar lateral -->
    @include('navbar')

    <!-- Header superior -->
    @include('header')

    <!-- Contenido dinámico -->
    <main class="bg-[rgb(232,236,237)] lg:ml-64 mt-4 lg:mt-16 p-6">
        @yield('content')
    </main>


    <!-- Mover scripts a la parte inferior para no bloquear el renderizado -->
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.2/dist/echo.iife.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/alpinejs"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/flatpickr.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <x:notify-messages />
    @notifyJs

    <script>
        document.addEventListener('click', function (event) {
            let notifDropdown = document.getElementById('dropdownNotificaciones');
            let notifButton = document.getElementById('notificacionesButton');

            if (!notifDropdown.contains(event.target) && !notifButton.contains(event.target)) {
                notifDropdown.classList.add('hidden');
            }
        });
    </script>

</body>

</html>