<!DOCTYPE html>
<html>

<head>
    <title>Estado de Solicitud de Vacaciones</title>
</head>

<body>
    <h1>Estado de tu Solicitud de Vacaciones</h1>

    <p>Hola {{ $empleado->name }},</p>

    <p>Tu solicitud de vacaciones para el periodo del {{ \Carbon\Carbon::parse($fechaInicio)->toFormattedDateString() }}
        al {{ \Carbon\Carbon::parse($fechaFin)->toFormattedDateString() }} ha sido {{ $estado }}.</p>

    <p>Si tienes alguna pregunta, no dudes en ponerte en contacto.</p>

    <p>Gracias por usar nuestro sistema de gestión de vacaciones.</p>
</body>

</html>