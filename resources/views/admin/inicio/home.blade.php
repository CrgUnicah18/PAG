{{-- resources/views/admin/inicio/home.blade.php --}}
@extends('layouts.app')

@section('content')

    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="bg-blue-500 text-white p-6 rounded-lg">
            <h3 class="text-lg font-semibold">Permisos Pendientes</h3>
            <p class="mt-2 text-2xl">{{ $permisosPendientes }}</p>
        </div>
        <div class="bg-blue-500 text-white p-6 rounded-lg">
            <h3 class="text-lg font-semibold">Permisos Pendientes de Aprobación</h3>
            <p class="mt-2 text-2xl">{{ $permisosPendienteAprobacion }}</p>
        </div>
        <div class="bg-green-500 text-white p-6 rounded-lg">
            <h3 class="text-lg font-semibold">Permisos Aprobados</h3>
            <p class="mt-2 text-2xl">{{ $permisosAprobados }}</p>
        </div>
        <div class="bg-red-500 text-white p-6 rounded-lg">
            <h3 class="text-lg font-semibold">Permisos Rechazados</h3>
            <p class="mt-2 text-2xl">{{ $permisosRechazados }}</p>
        </div>
        <div class="bg-yellow-500 text-white p-6 rounded-lg">
            <h3 class="text-lg font-semibold">Vacaciones Próximas</h3>
            <p class="mt-2 text-2xl">{{ $vacacionesProximas }}</p>
        </div>
        <div class="bg-gray-500 text-white p-6 rounded-lg">
            <h3 class="text-lg font-semibold">Empleados Totales</h3>
            <p class="mt-2 text-2xl">{{ $totalEmpleados }}</p>
        </div>
        <div class="bg-purple-500 text-white p-6 rounded-lg">
            <h3 class="text-lg font-semibold">Empleados Activos</h3>
            <p class="mt-2 text-2xl">{{ $empleadosActivos }}</p>
        </div>

        <!-- Cartilla de Cumpleaños -->
        <div class="col-span-1 sm:col-span-2 lg:col-span-3 p-4 bg-blue-100 rounded-lg shadow-sm mb-6">
            <h3 class="text-2xl font-semibold text-gray-800 mb-2 flex items-center">
                <span class="mr-2 text-3xl">🎉</span>
                ¡Cumpleaños de Hoy!
            </h3>
            @if ($cumpleañosHoy->isEmpty())
                <p class="text-lg text-gray-700">No hay cumpleaños hoy.</p>
            @else
                <ul class="list-disc pl-5">
                    @foreach ($cumpleañosHoy as $empleado)
                        <li class="text-lg text-gray-700">{{ $empleado->nombre }} {{ $empleado->apellido }}
                            {{ $empleado->oficina->nombre }}
                            {{ \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m') }}
                        </li>
                    @endforeach
                </ul>
            @endif

            <h3 class="text-2xl font-semibold text-gray-800 mt-6 mb-2 flex items-center">
                <span class="mr-2 text-3xl">🎂</span>
                ¡Cumpleaños de Mañana!
            </h3>
            @if ($cumpleañosMañana->isEmpty())
                <p class="text-lg text-gray-700">No hay cumpleaños mañana.</p>
            @else
                <ul class="list-disc pl-5">
                    @foreach ($cumpleañosMañana as $empleado)
                        <li class="text-lg text-gray-700">{{ $empleado->nombre }} {{ $empleado->apellido }}
                            ({{ \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m') }})</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

@endsection