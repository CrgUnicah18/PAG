<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Permisos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        .tabla-reporte {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .tabla-reporte th,
        .tabla-reporte td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .tabla-reporte th {
            background-color: #f4f4f4;
        }

        .reporte-detalles {
            margin-top: 20px;
        }

        .mes-segmentado {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .conteo {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>Reporte de Permisos</h1>

    <div class="reporte-detalles">
        <p><strong>Empleado:</strong> {{ $empleado ? $empleado->nombre : 'Todos los empleados' }}</p>
        <p><strong>Tipo de Permiso:</strong>
            {{ $tipoPermisoId && $tipoPermisoId != 'todos' ? $tiposPermiso->find($tipoPermisoId)->nombre : 'Todos los tipos' }}
        </p>
        <p><strong>Mes:</strong>
            {{ $mes && $mes != 'todos' ? \Carbon\Carbon::createFromFormat('m', $mes)->format('F') : 'Todos los meses' }}
        </p>
        <p><strong>Año:</strong> {{ $anio }}</p>
        <p><strong>Estado:</strong> {{ $estado ? ucfirst($estado) : 'Todos los estados' }}</p> <!-- Mostrar estado -->
    </div>

    <div class="mes-segmentado">
        @foreach($permisosAgrupados as $mesAnio => $permisosPorMes)
                <h3>Mes: {{ $mesAnio }}</h3>
                <table class="tabla-reporte">
                    <thead>
                        <tr>
                            <th>Empleado</th>
                            <th>Tipo de Permiso</th>
                            <th>Fecha de Inicio</th>
                            <th>Fecha de Fin</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $permisosFiltrados = $permisosPorMes->filter(function ($permiso) use ($estado) {
                                return !$estado || $permiso->estado == $estado;
                            });
                        @endphp

                        @foreach($permisosFiltrados as $permiso)
                            <tr>
                                <td>{{ $permiso->empleado->nombre }}</td>
                                <td>{{ $permiso->tipoPermiso->nombre }}</td>
                                <td>{{ $permiso->fecha_inicio }}</td>
                                <td>{{ $permiso->fecha_fin }}</td>
                                <td>{{ $permiso->estado }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Conteo de permisos filtrados por mes -->
                <div class="conteo">
                    <p><strong>Total de permisos en este mes:</strong> {{ $permisosFiltrados->count() }}</p>
                </div>
        @endforeach
    </div>

    <!-- Conteo total de permisos filtrados -->
    <div class="conteo">
        <p><strong>Total de permisos filtrados:</strong> {{ $permisosAgrupados->flatten()->filter(function ($permiso) use ($estado) {
    return !$estado || $permiso->estado == $estado;
})->count() }}</p>
    </div>

</body>

</html>