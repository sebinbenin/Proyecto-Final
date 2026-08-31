<?php

session_start();


if (!isset($_SESSION['usuario_id']) ||
    $_SESSION['rol'] !== 'administrador') {

    header("Location: ../login/login.php");

    exit();

}


require '../login/conexion.php';



$producto_id =
    intval($_POST['producto_id'] ?? 0);


$cantidad =
    intval($_POST['cantidad'] ?? 0);


$accion =
    $_POST['accion'] ?? '';



if ($producto_id <= 0 ||
    $cantidad <= 0) {

    header(
        "Location: panel_admin.php?stock=error"
    );

    exit();

}



// ==========================================
// OBTENER STOCK ACTUAL
// ==========================================

$sql = "SELECT
            stock_total,
            stock_disponible
        FROM productos
        WHERE id = :id";


$stmt = $conexion->prepare($sql);


$stmt->execute([
    ':id' => $producto_id
]);


$producto =
    $stmt->fetch(PDO::FETCH_ASSOC);



if (!$producto) {

    header(
        "Location: panel_admin.php?stock=no_existe"
    );

    exit();

}



$stockTotal =
    intval($producto['stock_total']);


$stockDisponible =
    intval($producto['stock_disponible']);



// ==========================================
// AGREGAR
// ==========================================

if ($accion === 'agregar') {


    $nuevoTotal =
        $stockTotal + $cantidad;


    $nuevoDisponible =
        $stockDisponible + $cantidad;

}



// ==========================================
// QUITAR
// ==========================================

elseif ($accion === 'quitar') {


    // No permitir quitar
    // más unidades de las disponibles

    if ($cantidad > $stockDisponible) {

        header(
            "Location: panel_admin.php?stock=insuficiente"
        );

        exit();

    }


    $nuevoTotal =
        $stockTotal - $cantidad;


    $nuevoDisponible =
        $stockDisponible - $cantidad;

}



else {

    header(
        "Location: panel_admin.php?stock=error"
    );

    exit();

}



// ==========================================
// ACTUALIZAR BASE DE DATOS
// ==========================================

$sql = "UPDATE productos

        SET
            stock_total = :stock_total,
            stock_disponible = :stock_disponible

        WHERE id = :id";


$stmt = $conexion->prepare($sql);


$stmt->execute([

    ':stock_total' =>
        $nuevoTotal,

    ':stock_disponible' =>
        $nuevoDisponible,

    ':id' =>
        $producto_id

]);



header(
    "Location: panel_admin.php?stock=actualizado"
);

exit();

?>