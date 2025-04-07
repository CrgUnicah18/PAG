<!-- resources/views/emails/alerta_vacaciones.blade.php -->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta de Vacaciones Pendientes</title>
</head>

<body>
    <p>Hola {{ $empleado->nombre }},</p>
    <p>Tienes {{ $empleado->vacaciones_restantes }} días de vacaciones pendientes.</p>
    <p>No olvides usarlos antes de que termine el año.</p>
    <p>Saludos,</p>
    <p>El equipo de Recursos Humanos</p>

</body>

</html>