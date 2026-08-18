<?php
session_start();

// Solo profesores
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'profesor') {
    header("Location: ../login/login.php");
    exit();
}

require '../login/conexion.php';

// Verificar que venga el ID de la reserva
if (!isset($_GET['id'])) {
    header("Location: mis_reservas.php");
    exit();
}

$id_reserva = $_GET['id'];
$id_usuario = $_SESSION['usuario_id'];

// Buscar la reserva y comprobar que sea del profesor
$sql = "SELECT reservas.*, productos.nombre
        FROM reservas
        INNER JOIN productos
        ON reservas.producto_id = productos.id
        WHERE reservas.id = ?
        AND reservas.usuario_id = ?";

$stmt = $conexion->prepare($sql);
$stmt->execute([$id_reserva, $id_usuario]);

$reserva = $stmt->fetch(PDO::FETCH_ASSOC);

// Si no existe o no pertenece a ese profesor
if (!$reserva) {
    die("Reserva no encontrada.");
}

// Solo se puede devolver si está aprobada
if ($reserva['estado'] != 'aprobada') {
    die("Esta reserva no se puede devolver.");
}

// Confirmar devolución
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Cambiar reserva a devuelta
    $sql = "UPDATE reservas
            SET estado = 'devuelta',
                fecha_devolucion = NOW()
            WHERE id = ?
            AND usuario_id = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([$id_reserva, $id_usuario]);

    // Devolver la cantidad al stock
    $sql = "UPDATE productos
            SET stock_disponible = stock_disponible + ?
            WHERE id = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        $reserva['cantidad'],
        $reserva['producto_id']
    ]);

    header("Location: mis_reservas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devolver producto</title>
</head>

<body>

    <h1>Devolver producto</h1>

    <p>
        Producto:
        <?php echo $reserva['nombre']; ?>
    </p>

    <p>
        Cantidad:
        <?php echo $reserva['cantidad']; ?>
    </p>

    <p>
        ¿Confirmás que querés devolver este producto?
    </p>

    <form method="POST">

        <button type="submit">
            Confirmar devolución
        </button>

    </form>

    <br>

    <a href="mis_reservas.php">Cancelar</a>

</body>

</html>