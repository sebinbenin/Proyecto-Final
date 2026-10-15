<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'alumno') {
    header("Location: ../login/login.php");
    exit();
}

require '../login/conexion.php';
require '../Assets/horario.php';

$stmt = $conexion->prepare("SELECT cursos.id, cursos.nombre
    FROM usuarios INNER JOIN cursos ON usuarios.curso_id = cursos.id WHERE usuarios.id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$curso = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../profesor/panel_profesor.css">
<link rel="stylesheet" href="../Assets/main.css">
<link rel="stylesheet" href="../Assets/horario.css">
<script src="../Assets/tema.js"></script>
<title>Mis horarios</title>
</head>
<body>
    <div class="panel">
        <?php include 'menu.php'; ?>
        <!-- CONTENIDO -->
        <main class="contenido">
            <header class="encabezado">
                <div>
                    <h1>Mis horarios</h1>
                    <p>Horario semanal de tu clase.</p>
                </div>
            </header>
            <?php if ($curso): ?>
                <?php mostrar_horario($conexion, $curso['id'], $curso['nombre']); ?>
            <?php else: ?>
                <p class="vacio">Todavía no tenés una clase asignada. Cuando el administrador te asigne una, vas a ver tu horario acá.</p>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
