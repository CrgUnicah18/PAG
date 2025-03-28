<header class="bg-[rgb(124,37,105)] text-white shadow-lg p-2 pl-72 flex items-center fixed top-0 right-0 w-[84%] z-10">
    <!-- Botón para abrir el sidebar en pantallas pequeñas -->
    <button id="sidebarToggle" class="lg:hidden text-white mr-4">
        <i class="fas fa-bars"></i> <!-- Ícono de menú -->
    </button>

    <!-- Contenedor de notificaciones -->
    <!-- Contenedor de notificaciones -->
    <div class="relative ml-auto mr-6">
        <button id="notificacionesButton" class="relative focus:outline-none">
            <i class="fas fa-bell text-white text-xl"></i>
            <?php
                // Filtrar las notificaciones no leídas que sean de estado pendiente o pendientes_aprobacion
                $notificacionesPendientes = Auth::user()->unreadNotifications->filter(function ($notificacion) {
                    return isset($notificacion->data['estado']) && in_array($notificacion->data['estado'], ['pendiente', 'pendientes_aprobacion']);
                });
            ?>
            <?php if($notificacionesPendientes->count() > 0): ?>
                <span class="absolute top-0 right-0 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                    <?php echo e($notificacionesPendientes->count()); ?>

                </span>
            <?php endif; ?>
        </button>

        <!-- Dropdown de notificaciones -->
        <div id="notificacionesDropdown"
            class="hidden absolute right-0 mt-2 w-64 bg-white text-black rounded-lg shadow-lg">
            <div class="p-2 text-center font-bold border-b">Notificaciones</div>
            <div class="max-h-60 overflow-y-auto">
                <?php $__empty_1 = true; $__currentLoopData = $notificacionesPendientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notificacion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <!-- Cuando se hace clic, se marca como leída y se redirige -->
                    <a href="<?php echo e(route('admin.notificaciones.index', $notificacion->id)); ?>"
                        class="block px-4 py-2 hover:bg-gray-200">
                        <?php echo e($notificacion->data['mensaje'] ?? 'Tienes una nueva notificación'); ?>

                    </a>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-center text-gray-500 py-2">No hay notificaciones pendientes</p>
                <?php endif; ?>
            </div>
            <div class="border-t text-center">
                <a href="<?php echo e(route('admin.notificaciones.index')); ?>"
                    class="block px-4 py-2 hover:bg-gray-200 text-blue-600">Ver todas</a>
            </div>
        </div>
    </div>




    <!-- Contenedor del menú del usuario -->
    <div class="relative">
        <button id="userMenuButton" class="flex items-center gap-3 focus:outline-none">
            <img src="<?php echo e(asset(Auth::user()->empleado->foto_perfil)); ?>" alt="Perfil"
                class="w-10 h-10 rounded-full object-cover shadow-lg shadow-[rgb(255,255,255)]">
            <h1 class="text-xl text-white"><?php echo e(Auth::user()->name); ?></h1>
        </button>

        <!-- Dropdown del menú de usuario -->
        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white text-black rounded-lg shadow-lg">
            <a href="#" class="block px-4 py-2 hover:bg-gray-200">Mi Perfil</a>
            <a href="<?php echo e(route('logout')); ?>" class="block px-4 py-2 hover:bg-gray-200">Cerrar sesión</a>
        </div>
    </div>
</header>

<script>

    // Dropdown de usuario
    document.getElementById('userMenuButton').addEventListener('click', function () {
        document.getElementById('userDropdown').classList.toggle('hidden');
    });

    // Dropdown de notificaciones
    document.getElementById('notificacionesButton').addEventListener('click', function () {
        document.getElementById('notificacionesDropdown').classList.toggle('hidden');
    });

    // Cierra los dropdowns si haces clic fuera
    document.addEventListener('click', function (event) {
        let userDropdown = document.getElementById('userDropdown');
        let userButton = document.getElementById('userMenuButton');
        let notifDropdown = document.getElementById('notificacionesDropdown');
        let notifButton = document.getElementById('notificacionesButton');

        if (!userDropdown.contains(event.target) && !userButton.contains(event.target)) {
            userDropdown.classList.add('hidden');
        }

        if (!notifDropdown.contains(event.target) && !notifButton.contains(event.target)) {
            notifDropdown.classList.add('hidden');
        }
    });
</script><?php /**PATH C:\laragon\www\PAG\resources\views/header.blade.php ENDPATH**/ ?>