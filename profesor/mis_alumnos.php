<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'profesor') {
    header("Location: ../login/login.php");
    exit();
}

require '../login/conexion.php';

// Cursos que dicta el profesor (con sus materias)
$stmt = $conexion->prepare("SELECT cursos.id, cursos.nombre, GROUP_CONCAT(profesor_curso.materia ORDER BY profesor_curso.materia SEPARATOR ', ') AS materias
    FROM profesor_curso INNER JOIN cursos ON profesor_curso.curso_id = cursos.id
    WHERE profesor_curso.profesor_id = ?
    GROUP BY cursos.id, cursos.nombre
    ORDER BY cursos.nombre");
$stmt->execute([$_SESSION['usuario_id']]);
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Alumnos aprobados de esos cursos
$stmt = $conexion->prepare("SELECT curso_id, nombre, correo, cedula, TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad
    FROM usuarios
    WHERE rol = 'alumno' AND estado = 'aprobado'
    AND curso_id IN (SELECT curso_id FROM profesor_curso WHERE profesor_id = ?)
    ORDER BY nombre");
$stmt->execute([$_SESSION['usuario_id']]);
$alumnos_por_curso = [];
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $alumno) {
    $alumnos_por_curso[$alumno['curso_id']][] = $alumno;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../profesor/panel_profesor.css">
<link rel="stylesheet" href="../Assets/main.css">
<script src="../Assets/tema.js"></script>
<title>Mis alumnos</title>
</head>
<body>
    <div class="panel">
        <!-- PANEL IZQUIERDO -->
        <section id="panel-izquierdo">
            <h2>Panel profesor</h2>
            <section class="opciones">
                <!-- INICIO -->
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 2.5 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 0 2 2H5a2 2 0 0 1-2-2z"/> <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg> <a href="panel_profesor.php">Inicio</a>
                </span>
                <!-- MIS RESERVAS -->
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg><a href="mis_reservas.php">Mis reservas</a>
                </span>
                <!-- RESERVAR -->
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
                    </svg><a href="productos.php">Reservar</a>
                </span>
                <!-- MIS ALUMNOS -->
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg><a href="mis_alumnos.php">Mis alumnos</a>
                </span>
                <!-- SALONES -->
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/>
                    </svg><a href="calendario.php">Salones</a>
                </span>
            </section>
            <!-- CERRAR SESIÓN -->
            <span id="cerrar">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/>
                    <line x1="21" x2="9" y1="12" y2="12"/>
                </svg><a href="../login/logout.php" class="cerrar-sesion">Cerrar sesión</a>
            </span>
        </section>
        <!-- CONTENIDO -->
        <main class="contenido">
            <header class="encabezado">
                <div>
                    <h1>Mis alumnos</h1>
                    <p>Alumnos de las clases que dictás.</p>
                </div>
            </header>

            <?php foreach ($cursos as $curso): ?>
                <section class="reservas lista-alumnos">
                    <div class="titulo-reservas">
                        <h2><?= htmlspecialchars($curso['nombre']) ?></h2>
                        <small><?= htmlspecialchars($curso['materias']) ?></small>
                    </div>
                    <?php $alumnos = $alumnos_por_curso[$curso['id']] ?? []; ?>
                    <?php if ($alumnos): ?>
                        <table class="tabla-alumnos">
                            <thead>
                                <tr><th>Nombre</th><th>Edad</th><th>Cédula</th><th>Correo</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alumnos as $alumno): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                                        <td><?= $alumno['edad'] !== null ? (int) $alumno['edad'] . ' años' : '-' ?></td>
                                        <td><?= htmlspecialchars($alumno['cedula'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($alumno['correo']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="vacio">Todavía no hay alumnos en esta clase.</p>
                    <?php endif; ?>
                </section>
            <?php endforeach; ?>

            <?php if (!$cursos): ?>
                <p class="vacio">Todavía no tenés clases asignadas. Pedile al administrador que te asigne un curso.</p>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
