<?php
session_start();

// 1. Seguridad: solo el administrador (profesor) puede devolver
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    die("Acceso denegado.");
}

require '../login/conexion.php';

// 2. Verificar que la solicitud venga por POST con el dato necesario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserva_id'])) {

    $reserva_id = (int) $_POST['reserva_id'];

    if ($reserva_id <= 0) {
        header("Location: mis_reservas.php?res=error");
        exit();
    }

    try {
        $conexion->beginTransaction();

        // 3. Buscar la reserva (solo si sigue activa, evita procesarla dos veces)
        $sqlBuscar = "SELECT producto_id, cantidad FROM reservas WHERE id = ? AND estado = 'activa'";
        $stmtBuscar = $conexion->prepare($sqlBuscar);
        $stmtBuscar->execute([$reserva_id]);
        $reserva = $stmtBuscar->fetch(PDO::FETCH_ASSOC);

        if (!$reserva) {
            // La reserva no existe o ya estaba devuelta
            $conexion->rollBack();
            header("Location: mis_reservas.php?res=error");
            exit();
        }

        // 4. Reponer el stock del producto
        $sqlUpdateStock = "UPDATE productos
                            SET stock_disponible = stock_disponible + ?
                            WHERE id = ?";
        $stmtUpdateStock = $conexion->prepare($sqlUpdateStock);
        $stmtUpdateStock->execute([$reserva['cantidad'], $reserva['producto_id']]);

        // 5. Marcar la reserva como devuelta
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

} else {
    header("Location: mis_reservas.php");
    exit();
}
?>
