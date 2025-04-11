<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formato de Vacaciones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 30px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
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
            margin-top: 40px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 6px;
            border: 1px solid #ccc;
        }

        .label {
            background-color: #f2f2f2;
            font-weight: bold;
            width: 25%;
        }

        .firma-empleado {
            text-align: center;
            width: 200px;
            height: 75px;
            border-bottom: 1px solid #000;
            margin: 40px auto 10px;
        }

        .firmas-tabla {
            width: 100%;
            margin-top: 50px;
            table-layout: fixed;
        }

        .firmas-tabla td {
            width: 50%;
            padding: 20px 10px 10px 10px;
            vertical-align: bottom;
        }

        .firma-linea {
            border-bottom: 1px solid #000;
            height: 70px;
            width: 80%;
            margin: 0 auto;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('images/PAG Vertical Logo.png') }}" alt="Logo">
        <h1>FORMATO DE SOLICITUD DE VACACIONES</h1>
        <p><strong>Proyecto Aldea Global</strong> {{ $vacacion->empleado->grupo->nombre ?? '---' }}</p>
    </div>

    <div class="section">
        <table class="data-table">
            <tr>
                <td class="label">Nombre</td>
                <td>{{ $vacacion->empleado->nombre }} {{ $vacacion->empleado->apellido }}</td>
                <td class="label">Cargo</td>
                <td>{{ $vacacion->empleado->cargo }}</td>
            </tr>
            <tr>
                <td class="label">Oficina</td>
                <td>{{ $vacacion->empleado->oficina->nombre ?? '---' }}</td>
                <td class="label">Fecha Solicitud</td>
                <td>{{ $vacacion->created_at->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">Fecha a Solicitar</td>
                <td colspan="3">
                    {{ \Carbon\Carbon::parse($vacacion->fecha_inicio)->format('d/m/Y') }} al
                    {{ \Carbon\Carbon::parse($vacacion->fecha_fin)->format('d/m/Y') }}
                </td>
            </tr>
            <tr>
                <td class="label">Duración</td>
                <td>{{ $vacacion->duracion_dias }} días</td>
                <td class="label">Fecha de Reintegro</td>
                <td>{{ \Carbon\Carbon::parse($vacacion->reintegro)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">Dias disponibles</td>
                <td>{{ $vacacion->empleado->vacaciones_restantes ?? 0 }} días</td>
                <td class="label">Periodo</td>
                <td>{{ $vacacion->periodo }} - {{ $vacacion->periodo + 1 }}</td>

            </tr>
            <tr>
                <td class="label">Motivo</td>
                <td colspan="3">{{ $vacacion->comentario ?? 'Sin comentario' }}</td>
            </tr>

            <tr>
                <td class="label">Tipo de Vacacion:</td>
                <td colspan="3">{{ $tipoPermiso->nombre }}</td>
            </tr>
            <tr>
                <td class="label">Fecha de ingreso:</td>
                <td colspan="3">{{ $fechaIngreso }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <p><strong>Lugar:</strong> {{ $vacacion->empleado->oficina->direccion ?? '---' }} &nbsp;&nbsp;
            <strong>Fecha:</strong> {{ now()->format('d/m/Y') }} &nbsp;&nbsp;
        </p>
    </div>

    <div class="section">
        <div class="firma-empleado"></div>
        <p><strong>Empleado (firma)</strong></p>
    </div>

    <div class="section">
        <p><strong>AUTORIZACIÓN:</strong></p>
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
    </div>

</body>

</html>