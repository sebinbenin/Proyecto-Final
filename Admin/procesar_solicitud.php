<?php
session_start();
// Añadimos ../ para buscar la conexión
require '../login/conexion.php'; 

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    die("Acceso denegado.");
}

// ... (código de actualización SQL) ...

// Aquí NO ponemos ../ porque panel_admin.php está en esta misma carpeta
header("Location: panel_admin.php");
exit();
?>