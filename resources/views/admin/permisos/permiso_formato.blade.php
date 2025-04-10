<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Hoja de Solicitud de Permiso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 30px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            float: left;
            width: 80px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0;
        }

        .section {
            text-align: center;
            margin-bottom: 15px;
            margin-top: 80px;
        }

        .data-table td {
            padding: 6px;
            border: 1px solid #ccc;
            margin-top: 30px;
        }

        .label {
            background-color: rgba(242, 242, 242, 0.7);
            font-weight: bold;
            width: 25%;
        }

        .firma-space {
            height: 50px;
            width: 50px;
            border-bottom: 1px solid #000;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
        }

        .firma-empleado {
            text-align: center;
            width: 200px;
            height: 75px;
            display: inline-block;
            border-bottom: 1px solid #000;
            margin-top: 40px;
        }

        /* Espacio adicional entre elementos */
        .espacio {
            margin-top: 40px;
        }

        .firmas-dos {
            display: flex;
            justify-content: space-around;
            margin-top: 80px;
        }

        .firma-bloque {
            text-align: center;
            width: 250px;
        }

        .firmas-tabla {
            width: 100%;
            margin-top: 140px;
            table-layout: fixed;
        }

        .firmas-tabla td {
            width: 50%;
            padding: 10px;
            vertical-align: bottom;
        }

        .firma-linea {
            border-bottom: 1px solid #000;
            height: 70px;
            width: 80%;
            margin: 0 auto;
        }



        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('images/logopag2.png') }}" alt="Logo">
        <h1>HOJA DE SOLICITUD DE PERMISO</h1>
        <p><strong>Proyecto Aldea Global</strong> {{ $permiso->empleado->grupo->nombre ?? '---' }}</p>
    </div>

    <div class="section">
        <table class="data-table">
            <tr>
                <td class="label">Nombre</td>
                <td>{{ $permiso->empleado->nombre . " " . $permiso->empleado->apellido }}</td>
                <td class="label">Cargo</td>
                <td>{{ $permiso->empleado->cargo }}</td>
            </tr>
            <tr>
                <td class="label">Oficina</td>
                <td>{{ $permiso->empleado->oficina->nombre ?? '---' }}</td>
                <td class="label">Fecha Solicitud</td>
                <td>{{ $permiso->created_at->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">Fecha a Solicitar</td>
                <td colspan="3">
                    {{ \Carbon\Carbon::parse($permiso->fecha_inicio)->format('d/m/Y') }} al
                    {{ \Carbon\Carbon::parse($permiso->fecha_fin)->format('d/m/Y') }}
                </td>
            </tr>
            <tr>
                <td class="label">Cantidad de Días</td>
                <td>{{ $permiso->dias_laborables }}</td>
                <td class="label">Fecha de Reintegro</td>
                <td>{{ \Carbon\Carbon::parse($permiso->fecha_fin)->addDay()->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">Motivo del Permiso</td>
                <td colspan="3">{{ $permiso->comentario }}</td>
            </tr>
            <tr>
                <td class="label">Tipo de Permiso:</td>
                <td colspan="3">{{ $tipoPermiso->nombre }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <p><strong>Lugar:</strong> {{ $permiso->empleado->oficina->direccion ?? '---' }} &nbsp;&nbsp;
            <strong>Fecha:</strong> {{ now()->format('d/m/Y') }}&nbsp;&nbsp;
            <strong>Periodo:</strong> {{ $permiso->periodo }}
        </p>
    </div>

    <div class="section espacio">
        <div class="firma-empleado"></div>
        <p><strong>Empleado (firma):</strong></p>

    </div>
    <table class="firmas-tabla">
        <tr>
            <td>
                <div class="firma-linea"></div>
            </td>
            <td>
                <div class="firma-linea"></div>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">Administración</td>
            <td style="text-align: center;">Jefe Inmediato</td>
        </tr>
    </table>



</body>

</html>