<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Sesión cerrada</title>
</head>
<body>
    <section>
        <h1>Sesión Cerrada</h1>
        <p>Has cerrado tu sesión correctamente.</p>
        <button onclick="window.location.href='../login/login.php'">Volver a iniciar sesión</button>
    </section>
</body>
</html>