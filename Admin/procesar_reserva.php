<?php
session_start();

// 1. Seguridad: solo el administrador (profesor) puede reservar
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    die("Acceso denegado.");
}

require '../login/conexion.php';

// 2. Verificar que la solicitud venga por POST con los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id']) && isset($_POST['cantidad'])) {

    $producto_id = (int) $_POST['producto_id'];
    $cantidad    = (int) $_POST['cantidad'];
    $usuario_id  = $_SESSION['usuario_id'];

    if ($producto_id <= 0 || $cantidad <= 0) {
        header("Location: panel_productos.php?res=error");
        exit();
    }

    try {
        $conexion->beginTransaction();

        // 3. Descontar stock SOLO si hay suficiente disponible.
        //    La condición "stock_disponible >= ?" en el WHERE evita que dos
        //    reservas simultáneas dejen el stock en negativo.
        $sqlUpdate = "UPDATE productos
                      SET stock_disponible = stock_disponible - ?
                      WHERE id = ? AND stock_disponible >= ?";
        $stmtUpdate = $conexion->prepare($sqlUpdate);
        $stmtUpdate->execute([$cantidad, $producto_id, $cantidad]);

        if ($stmtUpdate->rowCount() === 0) {
            // No había stock suficiente en el momento de reservar
            $conexion->rollBack();
            header("Location: panel_productos.php?res=sin_stock");
            exit();
        }

        // 4. Registrar la reserva
        $sqlInsert = "INSERT INTO reservas (producto_id, usuario_id, cantidad, fecha_reserva, estado)
                      VALUES (?, ?, ?, NOW(), 'activa')";
        $stmtInsert = $conexion->prepare($sqlInsert);
        $stmtInsert->execute([$producto_id, $usuario_id, $cantidad]);

        $conexion->commit();
        header("Location: panel_productos.php?res=reservado");
        exit();

    } catch (Exception $e) {
        $conexion->rollBack();
        header("Location: panel_productos.php?res=error");
        exit();
    }

} else {
    header("Location: panel_productos.php");
    exit();
}
?>
