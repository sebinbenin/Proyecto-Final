<?php
session_start();

// Solo administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login/login.php");
    exit();
}

require '../login/conexion.php';

// Verificar datos
if (!isset($_GET['id']) || !isset($_GET['accion'])) {
    header("Location: panel_admin.php");
    exit();
}

$id_reserva = $_GET['id'];
$accion = $_GET['accion'];

// Buscar reserva
$sql = "SELECT reservas.*, productos.stock_disponible
        FROM reservas
        INNER JOIN productos
        ON reservas.producto_id = productos.id
        WHERE reservas.id = ?";

$stmt = $conexion->prepare($sql);
$stmt->execute([$id_reserva]);

$reserva = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar que exista
if (!$reserva) {
    die("Reserva no encontrada.");
}

// Solo procesar pendientes
if ($reserva['estado'] != 'pendiente') {
    die("Esta reserva ya fue procesada.");
}

// APROBAR
if ($accion == 'aprobar') {

    if ($reserva['cantidad'] > $reserva['stock_disponible']) {
        die("No hay suficiente stock disponible.");
    }

    // Cambiar estado
    $sql = "UPDATE reservas
            SET estado = 'aprobada'
            WHERE id = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([$id_reserva]);

    // Restar stock
    $sql = "UPDATE productos
            SET stock_disponible = stock_disponible - ?
            WHERE id = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        $reserva['cantidad'],
        $reserva['producto_id']
    ]);

    header("Location: panel_admin.php");
    exit();
}

// RECHAZAR
if ($accion == 'rechazar') {

    $sql = "UPDATE reservas
            SET estado = 'rechazada'
            WHERE id = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([$id_reserva]);

    header("Location: panel_admin.php");
    exit();
}

// Si la acción no es válida
header("Location: panel_admin.php");
exit();
?>