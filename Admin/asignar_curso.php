<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login/login.php");
    exit();
}

require '../login/conexion.php';

$accion = $_REQUEST['accion'] ?? '';

if ($accion === 'nuevo_curso') {
    $nombre = trim($_POST['nombre_curso'] ?? '');
    if ($nombre !== '') {
        $stmt = $conexion->prepare("INSERT INTO cursos (nombre) VALUES (?)");
        $stmt->execute([$nombre]);
    }
} elseif ($accion === 'asignar') {
    $profesor_id = intval($_POST['profesor_id'] ?? 0);
    $curso_id = intval($_POST['curso_id'] ?? 0);
    $materia = trim($_POST['materia'] ?? '');
    if ($profesor_id > 0 && $curso_id > 0 && $materia !== '') {
        $stmt = $conexion->prepare("INSERT IGNORE INTO profesor_curso (profesor_id, curso_id, materia) VALUES (?, ?, ?)");
        $stmt->execute([$profesor_id, $curso_id, $materia]);
    }
} elseif ($accion === 'quitar') {
    $stmt = $conexion->prepare("DELETE FROM profesor_curso WHERE profesor_id = ? AND curso_id = ? AND materia = ?");
    $stmt->execute([$_GET['profesor_id'] ?? 0, $_GET['curso_id'] ?? 0, $_GET['materia'] ?? '']);
}

header("Location: panel_admin.php?vista=profesores");
exit();
?>
