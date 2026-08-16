<?php
$host = 'localhost';
$db = 'utec'; // Cambia esto por el nombre de tu base de datos
$user = 'root';
$pass = '';

try {
    $conexion = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>