<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login/login.php");
    exit();
}

require '../login/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: panel_admin.php");
    exit();
}

$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$stock = intval($_POST['stock'] ?? 0);

if ($nombre === '' || $descripcion === '' || $categoria === '' || $stock < 0) {
    header("Location: panel_admin.php?producto=error");
    exit();
}


/* =========================
   PROCESAR IMAGEN
   ========================= */

$nombreImagen = null;

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

    $archivo = $_FILES['imagen'];

    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($extension, $extensionesPermitidas)) {
        header("Location: panel_admin.php?producto=imagen_invalida");
        exit();
    }

    $nombreImagen = uniqid('producto_') . '.' . $extension;

    $carpeta = '../uploads/productos/';

    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }

    $ruta = $carpeta . $nombreImagen;

    if (!move_uploaded_file($archivo['tmp_name'], $ruta)) {
        header("Location: panel_admin.php?producto=error_imagen");
        exit();
    }
}


/* =========================
   INSERTAR PRODUCTO
   ========================= */

$sql = "INSERT INTO productos
        (nombre, descripcion, imagen, categoria, stock_total, stock_disponible)
        VALUES
        (:nombre, :descripcion, :imagen, :categoria, :stock_total, :stock_disponible)";

$stmt = $conexion->prepare($sql);

$stmt->execute([
    ':nombre' => $nombre,
    ':descripcion' => $descripcion,
    ':imagen' => $nombreImagen,
    ':categoria' => $categoria,
    ':stock_total' => $stock,
    ':stock_disponible' => $stock
]);

header("Location: panel_admin.php?producto=agregado");
exit();
?>