<?php
session_start();

// 1. Seguridad: Verificar que sea admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    die("Acceso denegado.");
}

require '../login/conexion.php';

// 2. Verificar que la URL traiga los parámetros necesarios
if (isset($_GET['id']) && isset($_GET['accion'])) {
    $id_profesor = $_GET['id'];
    $accion = $_GET['accion'];

    // Determinar el estado final según la opción presionada
    if ($accion === 'aprobar') {
        $nuevo_estado = 'aprobado';
    } elseif ($accion === 'rechazar') {
        $nuevo_estado = 'rechazado';
    } else {
        // Si la acción es inválida, regresar al panel
        header("Location: panel_admin.php");
        exit();
    }

    // 3. Actualizar la base de datos mediante PDO
    $sql = "UPDATE usuarios SET estado = ? WHERE id = ? AND rol = 'profesor'";
    $stmt = $conexion->prepare($sql);
    
    if ($stmt->execute([$nuevo_estado, $id_profesor])) {
        // Redirigir enviando la respuesta en la URL
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