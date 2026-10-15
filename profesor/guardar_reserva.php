<?php
session_start();
require '../login/conexion.php';

if (!isset($_SESSION['rol'])) {
    // Si no está logueado, lo mandamos al login
    header("Location: ../login/login.php");
    exit();
}
$rol_usuario = $_SESSION['rol'];

// Guarda un aviso para mostrarlo en la página siguiente y redirige.
// (No usamos alert() porque algunos navegadores bloquean esos cuadros y el usuario no se entera).
function avisar($tipo, $texto, $destino) {
    $_SESSION['aviso'] = ['tipo' => $tipo, 'texto' => $texto];
    header("Location: $destino");
    exit();
}

$fecha = $_POST['fecha'] ?? '';
$volver_formulario = 'reserva_salones.php?fecha=' . urlencode($fecha);

// Validar que se hayan enviado los datos requeridos
if (
    empty($_POST['fecha']) ||
    empty($_POST['salon']) ||
    empty($_POST['hora_inicio']) ||
    empty($_POST['hora_fin']) ||
    empty($_POST['profesor']) ||
    empty($_POST['curso'])
) {
    avisar('error', 'Faltan datos para realizar la reserva.', $volver_formulario);
}

$salon = $_POST['salon'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];
$profesor = $_POST['profesor'];
$curso = $_POST['curso'];

// Los alumnos solo pueden reservar la biblioteca y el anfiteatro (con fines de estudio)
if ($rol_usuario === 'alumno') {
    if (!in_array($salon, ['Biblioteca', 'Anfiteatro'])) {
        avisar('error', 'Los alumnos solo pueden reservar la Biblioteca o el Anfiteatro.', $volver_formulario);
    }
    // El nombre lo toma de la cuenta, no del formulario
    $profesor = $_SESSION['usuario_nombre'] ?? $profesor;
}

// No se puede reservar un día que ya pasó
if ($fecha < date('Y-m-d')) {
    avisar('error', 'No se puede reservar un día que ya pasó.', 'reserva_salones.php');
}

// Validar que la hora de inicio sea menor a la de fin
if ($hora_inicio >= $hora_fin) {
    avisar('error', 'La hora de inicio debe ser menor a la hora de finalización.', $volver_formulario);
}

try {
    // 1. Verificar si el salón ya está ocupado en ese horario
    $sql_verificar = "SELECT id FROM reservas_salones
                      WHERE fecha = ? AND salon = ?
                      AND hora_inicio < ? AND hora_fin > ?";

    $stmt_verificar = $conexion->prepare($sql_verificar);
    $stmt_verificar->execute([$fecha, $salon, $hora_fin, $hora_inicio]);

    if ($stmt_verificar->rowCount() > 0) {
        avisar('error', "El salón $salon ya está reservado en ese horario. Elegí otro horario u otro salón.", $volver_formulario);
    }

    // 2. Insertar la reserva si no hay cruce de horarios
    $sql_insertar = "INSERT INTO reservas_salones (fecha, salon, hora_inicio, hora_fin, profesor, curso)
                    VALUES (?, ?, ?, ?, ?, ?)";

    $stmt_insertar = $conexion->prepare($sql_insertar);
    $stmt_insertar->execute([$fecha, $salon, $hora_inicio, $hora_fin, $profesor, $curso]);

    // Vuelve al calendario en el mes de la reserva
    $partesFecha = explode('-', $fecha);
    $fecha_texto = date('d/m/Y', strtotime($fecha));
    avisar('ok', "Reserva guardada: $salon el $fecha_texto de $hora_inicio a $hora_fin.",
        "calendario.php?mes={$partesFecha[1]}&anio={$partesFecha[0]}");

} catch (PDOException $e) {
    avisar('error', 'Ocurrió un error al guardar la reserva. Intentá de nuevo.', $volver_formulario);
}
?>
