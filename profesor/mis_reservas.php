<?php
session_start();

// Solo profesores
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'profesor') {
    header("Location: ../login/login.php");
    exit();
}

require '../login/conexion.php';

$id_usuario = $_SESSION['usuario_id'];

// Traer solo las reservas del profesor que inició sesión
$sql = "SELECT reservas.*, productos.nombre
        FROM reservas
        INNER JOIN productos
        ON reservas.producto_id = productos.id
        WHERE reservas.usuario_id = ?
        ORDER BY reservas.fecha_reserva DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute([$id_usuario]);

$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis reservas</title>
</head>

<body>

    <h1>Mis reservas</h1>

    <a href="panel_profesor.php">Volver al panel</a>

    <hr>

    <?php if (count($reservas) == 0) { ?>

        <p>No tenés reservas todavía.</p>

    <?php } else { ?>

        <?php foreach ($reservas as $reserva) { ?>

            <div>

                <h3><?php echo $reserva['nombre']; ?></h3>

                <p>
                    Cantidad:
                    <?php echo $reserva['cantidad']; ?>
                </p>

                <p>
                    Fecha de reserva:
                    <?php echo $reserva['fecha_reserva']; ?>
                </p>

                <p>
                    Estado:
                    <?php echo $reserva['estado']; ?>
                </p>

                <?php if ($reserva['estado'] == 'aprobada') { ?>

                    <a href="devolver.php?id=<?php echo $reserva['id']; ?>">
                        Devolver producto
                    </a>

                <?php } ?>

            </div>

            <hr>

        <?php } ?>

    <?php } ?>

</body>

</html>