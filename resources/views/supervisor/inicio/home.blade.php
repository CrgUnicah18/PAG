{{-- resources/views/admin/inicio/home.blade.php --}}
@extends('layouts.app')

@section('content')
    @if(auth()->user()->roles->isEmpty())
        <p>Este usuario no tiene rol asignado.</p>
    @endif

    @if(auth()->user()->hasRole('admin'))
        <div>
            <h3>Bienvenido, Administrador</h3>
            <!-- contenido exclusivo para admin -->
        </div>
    @endif

    @if(auth()->user()->hasRole('supervisor'))
        <div>
            <h3>Bienvenido, Supervisor</h3>
            <!-- contenido exclusivo para supervisor -->
        </div>
    @endif

    @if(auth()->user()->hasRole('empleado'))
        <div>
            <h3>Bienvenido, Empleado</h3>
            <!-- contenido exclusivo para empleado -->
        </div>
    @endif

    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Otros bloques como permisos y vacaciones -->

        @if(auth()->user()->hasRole('supervisor'))
            <div class="bg-indigo-500 text-white p-6 rounded-lg">
                <h3 class="text-lg font-semibold">Empleados Asignados</h3>
                <p class="mt-2 text-2xl">{{ $empleadosAsignados->count() }}</p>
                <!-- Muestra la cantidad de empleados asignados -->
            </div>
        @endif

        <div class="bg-gray-500 text-white p-6 rounded-lg">
            <h3 class="text-lg font-semibold">Empleados Totales</h3>
            <p class="mt-2 text-2xl">{{ $totalEmpleados }}</p>
        </div>
        <div class="bg-purple-500 text-white p-6 rounded-lg">
            <h3 class="text-lg font-semibold">Empleados Activos</h3>
            <p class="mt-2 text-2xl">{{ $empleadosActivos }}</p>
        </div>

        <!-- Cumpleaños de hoy y mañana -->
        <div class="p-4 bg-blue-100 rounded-lg shadow-sm mb-6">
            <h3 class="text-2xl font-semibold text-gray-800 mb-2 flex items-center">
                <span class="mr-2 text-3xl">🎉</span>
                ¡Cumpleaños de Hoy!
            </h3>
            <ul class="list-disc pl-5">
                @foreach ($cumpleañosHoy as $empleado)
                    <li class="text-lg text-gray-700">{{ $empleado->nombre }}
                        ({{ \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m') }})</li>
                @endforeach
            </ul>

            <h3 class="text-2xl font-semibold text-gray-800 mt-6 mb-2 flex items-center">
                <span class="mr-2 text-3xl">🎂</span>
                ¡Cumpleaños de Mañana!
            </h3>
            <ul class="list-disc pl-5">
                @foreach ($cumpleañosMañana as $empleado)
                    <li class="text-lg text-gray-700">{{ $empleado->nombre }}
                        ({{ \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m') }})</li>
                @endforeach
            </ul>
        </div>
    </div>

@endsection