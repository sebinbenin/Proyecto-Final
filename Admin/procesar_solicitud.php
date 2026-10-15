<?php
session_start();


if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    die("Acceso denegado.");
}

require '../login/conexion.php';


if (isset($_GET['id']) && isset($_GET['accion'])) {
    $id_usuario = $_GET['id'];
    $accion = $_GET['accion'];


    if ($accion === 'aprobar') {
        $nuevo_estado = 'aprobado';
    } elseif ($accion === 'rechazar') {
        $nuevo_estado = 'rechazado';
    } else {

        header("Location: panel_admin.php");
        exit();
    }

    // Aplica tanto a profesores como a alumnos
    $sql = "UPDATE usuarios SET estado = ? WHERE id = ? AND rol IN ('profesor', 'alumno')";
    $stmt = $conexion->prepare($sql);

    if ($stmt->execute([$nuevo_estado, $id_usuario])) {

        header("Location: panel_admin.php?res=" . $nuevo_estado);
        exit();
    } else {
        echo "Error al actualizar la base de datos.";
    }
} else {
    header("Location: panel_admin.php");
    exit();
}
?>
