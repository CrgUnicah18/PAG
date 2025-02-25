@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card" style="max-width: 600px; margin: auto;">
            <div class="card-header text-center">
                <h3>Perfil de Empleado</h3>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <img src="{{ asset($empleado->foto_perfil) }}" alt="Foto de Perfil" class="img-fluid"
                        style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                </div>

                <h4 class="mt-3 text-center">{{ $empleado->nombre }} {{ $empleado->apellido }}</h4>

                <table class="table table-bordered mt-3">
                    <tr>
                        <th>Dirección</th>
                        <td>{{ $empleado->direccion }}</td>
                    </tr>
                    <tr>
                        <th>Teléfono</th>
                        <td>{{ $empleado->telefono }}</td>
                    </tr>
                    <tr>
                        <th>Fecha de Nacimiento</th>
                        <td>{{ \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Fecha de Ingreso</th>
                        <td>{{ \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Oficina</th>
                        <td>{{ $empleado->oficina->nombre }}</td>
                    </tr>
                    <tr>
                        <th>Grupo</th>
                        <td>{{ $empleado->grupo->nombre }}</td>
                    </tr>
                    <tr>
                        <th>Tipo de Contrato</th>
                        <td>{{ $empleado->tipoContrato ? $empleado->tipoContrato->nombre : 'No asignado' }}</td>

                    </tr>
                    <tr>
                        <th>Supervisor</th>
                        <td>{{ $empleado->supervisor ? $empleado->supervisor->nombre . ' ' . $empleado->supervisor->apellido : 'N/A' }}
                        </td>
                    </tr>
                </table>

                <div class="text-center mt-3">
                    @if ($empleado->documento_contrato)
                        <a href="{{ asset($empleado->documento_contrato) }}" class="btn btn-primary" target="_blank">Abrir
                            Contrato</a>
                    @else
                        <p>No se ha cargado un contrato aún.</p>
                    @endif
                </div>



            </div>
        </div>
    </div>
    </div>
@endsection