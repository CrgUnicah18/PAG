@php
    $bgColor = auth()->user()->hasRole('admin') ? 'bg-red-900' :
        (auth()->user()->hasRole('supervisor') ? 'bg-blue-900' : 'bg-green-900');
@endphp

<aside class="{{ $bgColor }} text-white w-64 h-screen fixed top-0 left-0 flex flex-col z-20">
    <!-- Sección de la imagen (bloque superior) -->
    <div class="p-4 text-center border-b-4 border-yellow-600">
        <img src="{{ asset('images/logopag2.png') }}" alt="Logo de Aldea Global" class="mx-auto w-32 h-auto">
    </div>

    <!-- Sección de navegación (bloque inferior) -->
    <nav class="flex-1 p-4">
        <ul class="space-y-2">
            <li>
                <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.inicio.home' : (auth()->user()->hasRole('supervisor') ? 'supervisor.inicio.home' : 'empleado.inicio.home')) }}"
                    class="block p-2 rounded-lg hover:bg-yellow-600 hover:text-white hover:border-none hover:shadow-xl hover:no-underline transition-all duration-300">
                    🏠 Inicio
                </a>
            </li>
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor') || auth()->user()->hasRole('empleado'))
                <li>
                    <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.anuncios.index' : (auth()->user()->hasRole('supervisor') ? 'supervisor.anuncios.index' : 'empleado.anuncios.index')) }}"
                        class="block p-2 rounded-lg hover:bg-yellow-600 hover:text-white hover:border-none hover:shadow-xl hover:no-underline transition-all duration-300">
                        📢 Anuncios
                    </a>
                </li>
            @endif

            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor'))
                <li>
                    <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.empleados.index' : 'supervisor.empleados.index') }}"
                        class="block p-2 rounded-lg hover:bg-yellow-600 hover:text-white hover:border-none hover:shadow-xl hover:no-underline transition-all duration-300">
                        👥 Empleados
                    </a>
                </li>
            @endif
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor') || auth()->user()->hasRole('empleado'))
                <li>
                    <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.permisos.index' : (auth()->user()->hasRole('supervisor') ? 'supervisor.permisos.index' : 'empleado.permisos.index')) }}"
                        class="block p-2 rounded-lg hover:bg-yellow-600 hover:text-white hover:border-none hover:shadow-xl hover:no-underline transition-all duration-300">
                        📜 Permisos
                    </a>
                </li>
            @endif
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor') || auth()->user()->hasRole('empleado'))
                <li>
                    <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.vacaciones.index' : (auth()->user()->hasRole('supervisor') ? 'supervisor.vacaciones.index' : 'empleado.vacaciones.index')) }}"
                        class="block p-2 rounded-lg hover:bg-yellow-600 hover:text-white hover:border-none hover:shadow-xl hover:no-underline transition-all duration-300">
                        🎉 Vacaciones
                    </a>
                </li>
            @endif
            @if(auth()->user()->hasRole('admin'))
                <li>
                    <a href="{{ route('admin.configuracion.index') }}"
                        class="block p-2 rounded-lg hover:bg-yellow-600 hover:text-white hover:border-none hover:shadow-xl hover:no-underline transition-all duration-300">
                        ⚙️ Configuración
                    </a>
                </li>
            @endif
        </ul>
    </nav>

    <!-- Sección de Cerrar sesión (al final del sidebar) -->
    <div class="p-4 mt-auto border-t border-gray-700">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="block w-full py-2 px-4 bg-red-600 text-white rounded-lg hover:bg-red-800 transition-all duration-300">
                <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
            </button>
        </form>
    </div>
</aside>