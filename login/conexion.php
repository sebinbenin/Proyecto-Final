<?php
// Hora de Uruguay, para que "hoy" sea el día correcto en el calendario
date_default_timezone_set('America/Montevideo');

$host = 'localhost';
$db = 'utec'; // Cambia esto por el nombre de tu base de datos
$user = 'root';
$pass = '';

try {
    $conexion = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Las reservas de salones de días que ya pasaron se borran solas
    $borrar_pasadas = $conexion->prepare("DELETE FROM reservas_salones WHERE fecha < ?");
    $borrar_pasadas->execute([date('Y-m-d')]);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
