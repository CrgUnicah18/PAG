<header class="bg-gray-800 text-white shadow-lg p-4 pl-72 flex items-center fixed top-0 right-0 w-[83%] z-10">
    <div class="ml-auto relative">
        <button id="userMenuButton" class="flex items-center gap-3 focus:outline-none">
            <img src="<?php echo e(asset(Auth::user()->empleado->foto_perfil)); ?>" alt="Perfil"
                class="w-10 h-10 rounded-full object-cover">
            <h1 class="text-xl"><?php echo e(Auth::user()->name); ?></h1>
        </button>

        <!-- Dropdown -->
        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white text-black rounded-lg shadow-lg">
            <a href="#" class="block px-4 py-2 hover:bg-gray-200">Mi Perfil</a>
        </div>
    </div>
</header>



<script>
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
</script><?php /**PATH C:\laragon\www\PAG\resources\views/header.blade.php ENDPATH**/ ?>