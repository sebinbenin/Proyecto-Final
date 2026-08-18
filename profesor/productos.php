<?php
session_start();


if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'profesor') {
    header("Location: ../login/login.php");
    exit();
}

require '../login/conexion.php';
include '../login/header.php';


$sql = "SELECT * FROM productos";
$stmt = $conexion->query($sql);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Préstamo de Productos</title>
</head>

<body>

    <h1>Préstamo de Productos</h1>

    <a href="panel_profesor.php">Volver al panel</a>

    <hr>

    <?php foreach ($productos as $producto) { ?>

        <div>

            <h3>
                <?php echo $producto['nombre']; ?>
            </h3>

            <p>
                <?php echo $producto['descripcion']; ?>
            </p>

            <p>
                Categoría:
                <?php echo $producto['categoria']; ?>
            </p>

            <p>
                Stock disponible:
                <?php echo $producto['stock_disponible']; ?>
            </p>

            <?php if ($producto['stock_disponible'] > 0) { ?>

                <a href="reservar.php?id=<?php echo $producto['id']; ?>">
                    Reservar
                </a>

            <?php } else { ?>

                <p>Sin stock</p>

            <?php } ?>

        </div>

        <hr>

    <?php } ?>

</body>

</html>