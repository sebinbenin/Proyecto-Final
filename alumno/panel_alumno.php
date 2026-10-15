<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'alumno') {
    header("Location: ../login/login.php");
    exit();
}

require '../login/conexion.php';

$stmt = $conexion->prepare("SELECT usuarios.nombre, cursos.nombre AS curso
    FROM usuarios LEFT JOIN cursos ON usuarios.curso_id = cursos.id WHERE usuarios.id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$alumno = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../profesor/panel_profesor.css">
<link rel="stylesheet" href="../Assets/main.css">
<script src="../Assets/tema.js"></script>
<title>Panel del Alumno</title>
</head>
<body>
    <div class="panel">
        <?php include 'menu.php'; ?>
        <!-- CONTENIDO -->
        <main class="contenido">
            <header class="encabezado">
                <div>
                    <h1>Hola, <?= htmlspecialchars($alumno['nombre'] ?? 'alumno') ?></h1>
                    <p><?= $alumno['curso'] ? 'Clase: ' . htmlspecialchars($alumno['curso']) : 'Todavía no tenés una clase asignada.' ?></p>
                </div>
            </header>
            <!-- TARJETAS -->
            <section class="tarjetas">
                <div class="tarjeta">
                    <div class="icono">🕒</div>
                    <h2>Mis horarios</h2>
                    <p>Consultá el horario semanal de tu clase.</p>
                    <a href="horarios.php" class="boton">Ver horarios</a>
                </div>
                <div class="tarjeta">
                    <div class="icono">💻</div>
                    <h2>Reservar equipos</h2>
                    <p>Pedí prestado un equipo para estudiar.</p>
                    <a href="../profesor/productos.php" class="boton">Nueva reserva</a>
                </div>
                <div class="tarjeta">
                    <div class="icono">📅</div>
                    <h2>Mis reservas</h2>
                    <p>Consultá los equipos que tenés reservados.</p>
                    <a href="../profesor/mis_reservas.php" class="boton">Ver reservas</a>
                </div>
                <div class="tarjeta">
                    <div class="icono">📚</div>
                    <h2>Salones</h2>
                    <p>Reservá la biblioteca o el anfiteatro para estudiar.</p>
                    <a href="../profesor/calendario.php" class="boton">Ver salones</a>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
