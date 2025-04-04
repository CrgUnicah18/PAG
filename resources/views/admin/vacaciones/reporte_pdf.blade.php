<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Vacaciones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 30px;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        .logo {
            width: 2cm;
            height: 2cm;
            margin-right: 20px;
        }

        .title-block {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .title-block h1 {
            font-size: 16px;
            margin: 0;
            font-weight: bold;
        }

        .title-block p {
            font-size: 12px;
            margin: 2px 0 0 0;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        table th {
            background-color: #f0f0f0;
            text-transform: uppercase;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #fafafa;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #999;
        }

        .totales {
            margin-top: 20px;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <!-- Logo a la izquierda -->
        <img src="{{ public_path('images/logopag2.png') }}" alt="Logo Empresa" class="logo">

        <!-- Título y subtítulo a la derecha del logo -->
        <div class="title-block">
            <h1>Reporte de Vacaciones de {{ $empleadoSeleccionado ? $empleadoSeleccionado->nombre : 'Todos los Empleados' }}</h1>
            <p>Generado el {{ now()->format('d-m-Y H:i:s') }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                @foreach($camposSeleccionados as $campo)
                    @if ($campo !== 'vacaciones_restantes') <!-- Excluir vacaciones_restantes de la tabla -->
                        <th>{{ ucfirst(str_replace('_', ' ', $campo)) }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($vacaciones as $vacacion)
                <tr>
                    @foreach($camposSeleccionados as $campo)
                        @if ($campo !== 'vacaciones_restantes') <!-- Excluir vacaciones_restantes de la tabla -->
                            <td>
                                @switch($campo)
                                    @case('empleado_id')
                                        {{ $vacacion->empleado->nombre . ' ' . $vacacion->empleado->apellido }}
                                        @break
                                    @case('tipo_permiso_id')
                                        {{ $vacacion->tipoPermiso->nombre }}
                                        @break
                                    @case('fecha_inicio')
                                        {{ \Carbon\Carbon::parse($vacacion->fecha_inicio)->format('Y-m-d') }}
                                        @break
                                    @case('fecha_fin')
                                        {{ \Carbon\Carbon::parse($vacacion->fecha_fin)->format('Y-m-d') }}
                                        @break
                                    @case('duracion_dias')
                                        {{ $vacacion->duracion_dias }}
                                        @break
                                    @case('periodo')
                                        {{ $vacacion->periodo }}
                                        @break
                                    @case('estado')
                                        {{ $vacacion->estado }}
                                        @break
                                    @case('comentario')
                                        {{ $vacacion->comentario }}
                                        @break
                                    @default
                                        {{ $vacacion->$campo ?? 'N/A' }}
                                @endswitch
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Mostrar los totales fuera de la tabla -->
    <div class="totales">
        <!-- Total de Vacaciones Restantes -->
        @if ($empleadoSeleccionado) <!-- Mostrar solo si se seleccionó un empleado específico -->
            Total de Vacaciones Restantes: 
            {{ $vacaciones->first()->empleado->vacaciones_restantes ?? 'N/A' }}
            <br>
        @endif

        <!-- Total de Vacaciones Aprobadas -->
        Total de Vacaciones Aprobadas (Duración en días): 
        {{ $vacaciones->where('estado', 'aprobadas')->sum('duracion_dias') }}
    </div>

    <div class="footer">
        <p>Reporte generado automáticamente por el Sistema de Recursos Humanos - Aldea Global.</p>
    </div>

</body>
</html>
