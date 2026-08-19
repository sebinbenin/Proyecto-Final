<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'profesor') {
    header("Location: ../login/login.php");
    exit();
}

require '../login/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['producto_id']) || !isset($_POST['cantidad'])) {
    header("Location: productos.php");
    exit();
}

$producto_id = (int) $_POST['producto_id'];
$cantidad    = (int) $_POST['cantidad'];
$usuario_id  = $_SESSION['usuario_id'];

if ($producto_id <= 0 || $cantidad <= 0) {
    header("Location: productos.php?res=error");
    exit();
}

try {
    $conexion->beginTransaction();

    $sqlInsert = "INSERT INTO reservas (producto_id, usuario_id, cantidad, fecha_reserva, estado)
                  VALUES (?, ?, ?, NOW(), 'pendiente')";
    $stmtInsert = $conexion->prepare($sqlInsert);
    $stmtInsert->execute([$producto_id, $usuario_id, $cantidad]);

    $conexion->commit();
    header("Location: productos.php?res=reservado");
    exit();
} catch (Exception $e) {
    $conexion->rollBack();
    header("Location: productos.php?res=error");
    exit();
}
?>
