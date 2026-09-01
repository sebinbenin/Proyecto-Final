<?php

session_start();
require '../login/conexion.php';


// VERIFICAR QUE SE RECIBIERON TODOS LOS DATOS

if (
    !isset($_POST['fecha']) ||
    !isset($_POST['salon']) ||
    !isset($_POST['hora_inicio']) ||
    !isset($_POST['hora_fin']) ||
    !isset($_POST['profesor']) ||
    !isset($_POST['curso'])
) {

    die("Faltan datos para realizar la reserva.");

}


// RECIBIR DATOS

$fecha = $_POST['fecha'];
$salon = $_POST['salon'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];
$profesor = $_POST['profesor'];
$curso = $_POST['curso'];


// VERIFICAR HORARIOS

if ($hora_inicio >= $hora_fin) {

    die("La hora de inicio debe ser menor que la hora de finalización.");

}


// VERIFICAR SI EL SALÓN YA ESTÁ RESERVADO

$sql_verificar = "
    SELECT id
    FROM reservas_salones
    WHERE fecha = ?
    AND salon = ?
    AND hora_inicio < ?
    AND hora_fin > ?
";


$stmt_verificar = $conexion->prepare($sql_verificar);


if (!$stmt_verificar) {

    die("Error en la consulta: " . $conexion->error);

}


$stmt_verificar->bind_param(
    "ssss",
    $fecha,
    $salon,
    $hora_fin,
    $hora_inicio
);


$stmt_verificar->execute();

$resultado_verificar = $stmt_verificar->get_result();


if ($resultado_verificar->num_rows > 0) {

    $stmt_verificar->close();

    echo "<script>

        alert('El salón ya está reservado en ese horario.');

        window.location.href =
        'nueva_reserva.php?fecha=" . urlencode($fecha) . "';

    </script>";

    exit();

}


$stmt_verificar->close();


// GUARDAR LA RESERVA

$sql = "
    INSERT INTO reservas_salones
    (
        fecha,
        salon,
        hora_inicio,
        hora_fin,
        profesor,
        curso
    )
    VALUES (?, ?, ?, ?, ?, ?)
";


$stmt = $conexion->prepare($sql);


if (!$stmt) {

    die("Error al preparar la reserva: " . $conexion->error);

}


$stmt->bind_param(
    "ssssss",
    $fecha,
    $salon,
    $hora_inicio,
    $hora_fin,
    $profesor,
    $curso
);


// EJECUTAR

if ($stmt->execute()) {

    // Volver a las reservas del día

    header(
        "Location: reservas_dias.php?fecha=" . urlencode($fecha)
    );

    exit();

} else {

    die(
        "Error al guardar la reserva: " .
        $stmt->error
    );

}


$stmt->close();

$conexion->close();

?>

