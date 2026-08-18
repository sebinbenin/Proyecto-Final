<?php
session_start();

// Solo profesores
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'profesor') {
    header("Location: ../login/login.php");
    exit();
}

require '../login/conexion.php';

// Verificar que venga el ID del producto
if (!isset($_GET['id'])) {
    header("Location: productos.php");
    exit();
}

$id_producto = $_GET['id'];
$id_usuario = $_SESSION['usuario_id'];

// Buscar el producto
$sql = "SELECT * FROM productos WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->execute([$id_producto]);

$producto = $stmt->fetch(PDO::FETCH_ASSOC);

// Si no existe
if (!$producto) {
    die("Producto no encontrado.");
}

// Si no hay stock
if ($producto['stock_disponible'] <= 0) {
    die("Este producto no tiene stock disponible.");
}

// Cuando el profesor confirma
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $cantidad = $_POST['cantidad'];

    // Verificar cantidad
    if ($cantidad <= 0 || $cantidad > $producto['stock_disponible']) {
        $mensaje = "Cantidad no válida.";
    } else {

        // Guardar solicitud como pendiente
        $sql = "INSERT INTO reservas
                (producto_id, usuario_id, cantidad, fecha_reserva, estado)
                VALUES (?, ?, ?, NOW(), 'pendiente')";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            $id_producto,
            $id_usuario,
            $cantidad
        ]);

        $mensaje = "Solicitud de reserva enviada correctamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar producto</title>
</head>

<body>

    <h1>Reservar producto</h1>

    <?php if (isset($mensaje)) { ?>
        <p><?php echo $mensaje; ?></p>
    <?php } ?>

    <h2><?php echo $producto['nombre']; ?></h2>

    <p>
        <?php echo $producto['descripcion']; ?>
    </p>

    <p>
        Stock disponible:
        <?php echo $producto['stock_disponible']; ?>
    </p>

    <form method="POST">

        <label>Cantidad:</label>

        <input
            type="number"
            name="cantidad"
            min="1"
            max="<?php echo $producto['stock_disponible']; ?>"
            required
        >

        <br><br>

        <button type="submit">
            Confirmar reserva
        </button>

    </form>

    <br>

    <a href="productos.php">Volver a productos</a>

</body>

</html>