<!-- resources/views/admin/permisos/pdf_reporte.blade.php -->
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
    </style>
</head>

<body>
    <h1>Reporte de Permisos</h1>

    <div class="reporte-detalles">
        <p><strong>Empleados:</strong> {{ $empleado ? $empleado->nombre : 'Todos los empleados' }}</p>
        <p><strong>Tipo de Permiso:</strong>
            {{ $tipoPermisoId ? $tiposPermiso->find($tipoPermisoId)->nombre : 'Todos los tipos' }}</p>
        <p><strong>Mes:</strong>
            {{ $mes ? \Carbon\Carbon::createFromFormat('m', $mes)->format('F') : 'Todos los meses' }}</p>
        <p><strong>Año:</strong> {{ $anio }}</p>
    </div>

    <table class="tabla-reporte">
        <thead>
            <tr>
                <th>Empleado</th>
                <th>Tipo de Permiso</th>
                <th>Fecha de Inicio</th>
                <th>Fecha de Fin</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permisos as $permiso)
                <tr>
                    <td>{{ $permiso->empleado->nombre }}</td>
                    <td>{{ $permiso->tipo_permiso }}</td>
                    <td>{{ $permiso->fecha_inicio }}</td>
                    <td>{{ $permiso->fecha_fin }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>