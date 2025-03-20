<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
</head>

<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>Restablecer Contraseña</h2>
    <p>Para restablecer tu contraseña, ingresa el PIN que se te ha enviado por correo y tu nueva contraseña.</p>

    <!-- Formulario para ingresar el PIN y la nueva contraseña -->
    <form action="{{ route('password.reset.submit') }}" method="POST">
        @csrf

        <!-- Campo para el PIN -->
        <div style="margin-bottom: 10px;">
            <label for="pin">PIN</label>
            <input type="text" name="pin" id="pin" required
                style="padding: 10px; width: 100%; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        <!-- Campo para la nueva contraseña -->
        <div style="margin-bottom: 10px;">
            <label for="new_password">Nueva Contraseña</label>
            <input type="password" name="new_password" id="new_password" required
                style="padding: 10px; width: 100%; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        <!-- Campo para confirmar la nueva contraseña -->
        <div style="margin-bottom: 10px;">
            <label for="new_password_confirmation">Confirmar Nueva Contraseña</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                style="padding: 10px; width: 100%; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        <button type="submit"
            style="padding: 10px 20px; background-color: #007BFF; color: white; border: none; border-radius: 5px;">
            Restablecer Contraseña
        </button>
    </form>
</body>

</html>