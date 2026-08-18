<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'profesor') {
header("Location: ../login/login.php");
exit();
}
include '../login/header.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel del Profesor</title>
</head>
<body>
<h1>Panel del Profesor</h1>
<p>Bienvenido al sistema de préstamos.</p>
<a href="productos.php">Ver productos</a>    <br><br>
<a href="mis_reservas.php">Mis reservas</a>
</body>
</html>