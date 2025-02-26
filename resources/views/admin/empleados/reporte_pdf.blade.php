<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reporte de Empleados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
    <h2 style="text-align: center;">Reporte de Empleados</h2>
    <table>
        <thead>
            <tr>
                @foreach($camposSeleccionados as $campo)
                    <th>{{ ucfirst(str_replace('_', ' ', $campo)) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($empleados as $empleado)
                <tr>
                    @foreach($camposSeleccionados as $campo)
                        <td>
                            @if($campo === 'oficina')
                                {{ $empleado->oficina ? $empleado->oficina->nombre : 'N/A' }}
                            @elseif($campo === 'grupo')
                                {{ $empleado->grupo ? $empleado->grupo->nombre : 'N/A' }}
                            @elseif($campo === 'tipo_contrato')
                                {{ $empleado->tipoContrato ? $empleado->tipoContrato->nombre : 'N/A' }}
                            @else
                                {{ $empleado->$campo }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>