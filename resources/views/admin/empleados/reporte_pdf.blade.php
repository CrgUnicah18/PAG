<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reporte de Empleados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-weight: bold;
            font-size: 18px;
            margin: 0;
            line-height: 1.2;
        }

        .header h2 {
            font-size: 16px;
            margin: 5px 0;
        }

        .logo {
            position: absolute;
            top: 2mm;
            /* Ajustamos la posición para que quede más arriba */
            left: 10mm;
            /* Mantenerlo en la esquina izquierda */
            width: 30mm;
            /* Hacemos el logo más pequeño (3 cm) */
            height: auto;
            object-fit: contain;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 50px;
            /* Aumentamos el margen superior para no sobreponerse al título */
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Logo en la esquina superior izquierda, más pequeño y ajustado -->
        <img src="{{ public_path('images/logopag2.png') }}" alt="Logo Empresa" class="logo">

        <!-- Título centrado -->
        <div class="header">
            <h1>Proyecto Aldea Global</h1>
            <h2>Ciudad de Siguatepeque. Reporte de Empleados</h2>
        </div>

        <!-- Tabla con los datos -->
        <table>
            <thead>
                <tr>
                    @foreach($camposSeleccionados as $campo)
                        <th>{{ $campos[$campo] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($empleados as $empleado)
                    <tr>
                        @foreach($camposSeleccionados as $campo)
                            <td>
                                @if($campo == 'nombre')
                                    {{ $empleado->nombre }}
                                @elseif($campo == 'dn')
                                    {{ $empleado->dn }}
                                @elseif($campo == 'apellido')
                                    {{ $empleado->apellido }}
                                @elseif($campo == 'direccion')
                                    {{ $empleado->direccion }}
                                @elseif($campo == 'telefono')
                                    {{ $empleado->telefono }}
                                @elseif($campo == 'fecha_nacimiento')
                                    {{ $empleado->fecha_nacimiento }}
                                @elseif($campo == 'fecha_ingreso')
                                    {{ $empleado->fecha_ingreso }}
                                @elseif($campo == 'oficina')
                                    {{ $empleado->oficina->nombre ?? '' }}
                                @elseif($campo == 'grupo')
                                    {{ $empleado->grupo->nombre ?? '' }}
                                @elseif($campo == 'tipo_contrato')
                                    {{ $empleado->tipoContrato->nombre ?? '' }}
                                @elseif($campo == 'rol')
                                    @foreach($empleado->roles as $rol)
                                        {{ $rol }}
                                    @endforeach
                                @elseif($campo == 'email')
                                    {{ $empleado->email }}
                                @elseif($campo == 'cargo')
                                    {{ $empleado->cargo }}
                                @elseif($campo == 'vacaciones_tomadas')
                                    {{ $empleado->vacaciones_tomadas }}
                                @elseif($campo == 'vacaciones_restantes')
                                    {{ $empleado->vacaciones_restantes }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>