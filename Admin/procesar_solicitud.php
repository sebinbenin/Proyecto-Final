<?php
session_start();


if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    die("Acceso denegado.");
}

require '../login/conexion.php';


if (isset($_GET['id']) && isset($_GET['accion'])) {
    $id_profesor = $_GET['id'];
    $accion = $_GET['accion'];

    
    if ($accion === 'aprobar') {
        $nuevo_estado = 'aprobado';
    } elseif ($accion === 'rechazar') {
        $nuevo_estado = 'rechazado';
    } else {
        
        header("Location: panel_admin.php");
        exit();
    }

    
    $sql = "UPDATE usuarios SET estado = ? WHERE id = ? AND rol = 'profesor'";
    $stmt = $conexion->prepare($sql);
    
    if ($stmt->execute([$nuevo_estado, $id_profesor])) {
        
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