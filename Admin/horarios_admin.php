<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login/login.php");
    exit();
}

require '../login/conexion.php';

$accion = $_REQUEST['accion'] ?? '';
$curso_id = intval($_REQUEST['curso_id'] ?? 0);

if ($accion === 'agregar') {
    $dia = intval($_POST['dia'] ?? 0);
    $hora_inicio = $_POST['hora_inicio'] ?? '';
    $hora_fin = $_POST['hora_fin'] ?? '';
    $materia = trim($_POST['materia'] ?? '');
    $profesor_id = intval($_POST['profesor_id'] ?? 0);

    if ($curso_id > 0 && $dia >= 1 && $dia <= 5 && $materia !== '' && $hora_inicio !== '' && $hora_inicio < $hora_fin) {
        $stmt = $conexion->prepare("INSERT INTO horarios (curso_id, dia, hora_inicio, hora_fin, materia, profesor_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$curso_id, $dia, $hora_inicio, $hora_fin, $materia, $profesor_id > 0 ? $profesor_id : null]);

        // El profesor también queda asignado a ese curso con esa materia
        if ($profesor_id > 0) {
            $stmt = $conexion->prepare("INSERT IGNORE INTO profesor_curso (profesor_id, curso_id, materia) VALUES (?, ?, ?)");
            $stmt->execute([$profesor_id, $curso_id, $materia]);
        }
    }
} elseif ($accion === 'borrar') {
    $stmt = $conexion->prepare("DELETE FROM horarios WHERE id = ?");
    $stmt->execute([intval($_GET['id'] ?? 0)]);
}

header("Location: panel_admin.php?vista=horarios&curso_id=" . $curso_id);
exit();
?>
