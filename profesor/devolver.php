<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'profesor') {
    header("Location: ../login/login.php");
    exit();
}

require '../login/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['reserva_id'])) {
    header("Location: mis_reservas.php");
    exit();
}

$reserva_id = (int) $_POST['reserva_id'];

if ($reserva_id <= 0) {
    header("Location: mis_reservas.php?res=error");
    exit();
}

try {
    $conexion->beginTransaction();

    $sqlBuscar = "SELECT producto_id, cantidad FROM reservas WHERE id = ? AND estado = 'aprobada'";
    $stmtBuscar = $conexion->prepare($sqlBuscar);
    $stmtBuscar->execute([$reserva_id]);
    $reserva = $stmtBuscar->fetch(PDO::FETCH_ASSOC);

    if (!$reserva) {
        $conexion->rollBack();
        header("Location: mis_reservas.php?res=error");
        exit();
    }

    $sqlUpdateStock = "UPDATE productos
                       SET stock_disponible = stock_disponible + ?
                       WHERE id = ?";
    $stmtUpdateStock = $conexion->prepare($sqlUpdateStock);
    $stmtUpdateStock->execute([$reserva['cantidad'], $reserva['producto_id']]);

    $sqlUpdateReserva = "UPDATE reservas
                         SET estado = 'devuelta', fecha_devolucion = NOW()
                         WHERE id = ?";
    $stmtUpdateReserva = $conexion->prepare($sqlUpdateReserva);
    $stmtUpdateReserva->execute([$reserva_id]);

    $conexion->commit();
    header("Location: mis_reservas.php?res=devuelto");
    exit();
} catch (Exception $e) {
    $conexion->rollBack();
    header("Location: mis_reservas.php?res=error");
    exit();
}
?>