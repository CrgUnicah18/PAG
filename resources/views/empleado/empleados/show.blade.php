@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="max-w-lg w-full bg-white p-8 rounded-2xl shadow-lg">
            <div class="text-center mb-6">
                <h3 class="text-2xl font-extrabold text-gray-800">Perfil de Empleado</h3>
            </div>
            <div class="text-center">
                <img src="{{ asset($empleado->foto_perfil) }}" alt="Foto de Perfil">

            </div>
            <h4 class="mt-4 text-center text-xl font-semibold text-gray-900">{{ $empleado->nombre }}
                {{ $empleado->apellido }}
            </h4>
            <div class="mt-6 bg-gray-50 p-4 rounded-lg shadow-inner">
                <table class="w-full text-gray-800 text-sm">
                    <tbody>
                        <tr class="border-b">
                            <th class="text-left py-2 font-semibold">Dirección</th>
                            <td class="py-2">{{ $empleado->direccion }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="text-left py-2 font-semibold">Teléfono</th>
                            <td class="py-2">{{ $empleado->telefono }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="text-left py-2 font-semibold">Fecha de Nacimiento</th>
                            <td class="py-2">{{ \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m/Y') }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="text-left py-2 font-semibold">Fecha de Ingreso</th>
                            <td class="py-2">{{ \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="text-left py-2 font-semibold">Oficina</th>
                            <td class="py-2">{{ $empleado->oficina->nombre }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="text-left py-2 font-semibold">Grupo</th>
                            <td class="py-2">{{ $empleado->grupo->nombre }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="text-left py-2 font-semibold">Tipo de Contrato</th>
                            <td class="py-2">{{ $empleado->tipoContrato ? $empleado->tipoContrato->nombre : 'No asignado' }}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-left py-2 font-semibold">Supervisor</th>
                            <td class="py-2">
                                {{ $empleado->supervisor ? $empleado->supervisor->nombre . ' ' . $empleado->supervisor->apellido : 'N/A' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-6">
                @if ($empleado->documento_contrato)
                    <a href="{{ asset($empleado->documento_contrato) }}"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-transform transform hover:scale-105">Abrir
                        Contrato</a>
                @else
                    <p class="text-gray-600">No se ha cargado un contrato aún.</p>
                @endif
            </div>
        </div>
    </div>
@endsection