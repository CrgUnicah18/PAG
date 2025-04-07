@include('notify::components.notify')

@extends('layouts.app')


@section('content')
    @if(auth()->user()->hasRole('supervisor'))
        <div>
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Muestra la cantidad de empleados bajo la supervisión -->
                <div class="bg-indigo-500 text-white p-6 rounded-lg">
                    <h3 class="text-lg font-semibold">Empleados Bajo Supervisión</h3>
                    <p class="mt-2 text-2xl">{{ $empleadosAsignados->count() ?? 0 }} empleados</p>
                </div>
                <!-- Muestra los permisos de los empleados bajo supervisión -->
                <div class="bg-yellow-500 text-white p-6 rounded-lg">
                    <h3 class="text-lg font-semibold">Permisos Pendientes</h3>
                    <p class="mt-2 text-2xl">{{ $permisosPendientes ?? 0 }} permisos</p>
                </div>

                <div class="bg-green-500 text-white p-6 rounded-lg">
                    <h3 class="text-lg font-semibold">Permisos Aprobados</h3>
                    <p class="mt-2 text-2xl">{{ $permisosAprobados ?? 0 }} permisos</p> <!-- Aquí debe ir $permisosAprobados -->
                </div>

                <div class="bg-red-500 text-white p-6 rounded-lg">
                    <h3 class="text-lg font-semibold">Permisos Rechazados</h3>
                    <p class="mt-2 text-2xl">{{ $permisosRechazados ?? 0 }} permisos</p>
                    <!-- Aquí debe ir $permisosRechazados -->
                </div>


                <!-- Muestra los permisos del propio supervisor -->
                <div class="bg-purple-500 text-white p-6 rounded-lg">
                    <h3 class="text-lg font-semibold">Permisos del Supervisor</h3>
                    <p class="mt-2 text-2xl">{{ $permisosSupervisor->count() ?? 0 }} permisos</p>
                </div>
                <!-- Cumpleaños de hoy y mañana -->
                <div class="p-4 bg-blue-100 rounded-lg shadow-sm mb-6">
                    <!-- Cumpleaños de Hoy -->
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2 flex items-center">
                        <span class="mr-2 text-3xl">🎉</span>
                        ¡Cumpleaños de Hoy!
                    </h3>
                    @if ($cumpleañosHoy->isEmpty())
                        <p class="text-lg text-gray-700">No hay cumpleaños hoy.</p>
                    @else
                        <ul class="list-disc pl-5">
                            @foreach ($cumpleañosHoy as $empleado)
                                <li class="text-lg text-gray-700">{{ $empleado->nombre }}
                                    ({{ \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m') }})</li>
                            @endforeach
                        </ul>
                    @endif

                    <!-- Cumpleaños de Mañana -->
                    <h3 class="text-2xl font-semibold text-gray-800 mt-6 mb-2 flex items-center">
                        <span class="mr-2 text-3xl">🎂</span>
                        ¡Cumpleaños de Mañana!
                    </h3>
                    @if ($cumpleañosMañana->isEmpty())
                        <p class="text-lg text-gray-700">No hay cumpleaños mañana.</p>
                    @else
                        <ul class="list-disc pl-5">
                            @foreach ($cumpleañosMañana as $empleado)
                                <li class="text-lg text-gray-700">{{ $empleado->nombre }}
                                    ({{ \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m') }})</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    @elseif(auth()->user()->hasRole('empleado'))
        <div>
            <h3>Bienvenido, Empleado</h3>

            <div class="mt-8">
                <h3 class="text-lg font-semibold">Tus Permisos</h3>
                <p class="mt-2 text-2xl">{{ $permisosEmpleado->count() ?? 0 }} permisos</p>
            </div>
        </div>
    @elseif(auth()->user()->hasRole('admin'))
        <div>
            <h3>Bienvenido, Administrador</h3>

            <div class="mt-8">
                <h3 class="text-lg font-semibold">Permisos del Empleado</h3>
                <p class="mt-2 text-2xl">{{ $permisosAdmin->count() ?? 0 }} permisos</p>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-semibold">Permisos Filtrados</h3>
                <p class="mt-2 text-2xl">{{ $permisos->count() ?? 0 }} permisos</p>
            </div>
        </div>
    @endif
@endsection