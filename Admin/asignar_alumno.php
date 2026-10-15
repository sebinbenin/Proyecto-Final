<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login/login.php");
    exit();
}

require '../login/conexion.php';

$alumno_id = intval($_POST['alumno_id'] ?? 0);
$curso_id = intval($_POST['curso_id'] ?? 0);

if ($alumno_id > 0) {
    // Un curso vacío deja al alumno sin clase
    $stmt = $conexion->prepare("UPDATE usuarios SET curso_id = ? WHERE id = ? AND rol = 'alumno'");
    $stmt->execute([$curso_id > 0 ? $curso_id : null, $alumno_id]);
}

header("Location: panel_admin.php?vista=alumnos");
exit();
?>
