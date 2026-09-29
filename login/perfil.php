<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require 'conexion.php';

$id = $_SESSION['usuario_id'];

$stmt = $conexion->prepare("SELECT nombre, correo, rol, foto FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

$sql_cursos = "SELECT cursos.nombre AS curso, profesor_curso.materia
                FROM profesor_curso
                INNER JOIN cursos
                ON profesor_curso.curso_id = cursos.id
                WHERE profesor_curso.profesor_id = ?";
$stmt = $conexion->prepare($sql_cursos);
$stmt->execute([$id]);
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql_companeros = "SELECT usuarios.nombre, usuarios.correo, usuarios.foto, cursos.nombre AS curso, profesor_curso.materia
                    FROM profesor_curso
                    INNER JOIN usuarios
                    ON profesor_curso.profesor_id = usuarios.id
                    INNER JOIN cursos
                    ON profesor_curso.curso_id = cursos.id
                    WHERE profesor_curso.curso_id IN (SELECT curso_id FROM profesor_curso WHERE profesor_id = ?)
                    AND profesor_curso.profesor_id != ?
                    AND usuarios.estado = 'aprobado'
                    ORDER BY cursos.nombre, usuarios.nombre";
$stmt = $conexion->prepare($sql_companeros);
$stmt->execute([$id, $id]);
$companeros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/main.css">
    <title>Mi Perfil</title>
</head>
<body>
    <header id="header"></header>
    <main>
        <h2>Mi Perfil</h2>
        <img src="../Assets/imagenes/<?php echo $usuario['foto'] ? 'perfiles/' . htmlspecialchars($usuario['foto']) : 'foto-usuario.png'; ?>" alt="Foto de perfil" width="120" height="120">
        <form action="subir_foto.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="foto" accept="image/*" required>
            <button type="submit">Cambiar foto</button>
        </form>
        <?php if (isset($_GET['foto'])): ?>
            <p style="color: red;">No se pudo subir la foto. Usá una imagen JPG, PNG o WEBP de hasta 2 MB.</p>
        <?php endif; ?>
        <p><b>Nombre:</b> <?php echo htmlspecialchars($usuario['nombre'] ?? 'Sin nombre'); ?></p>
        <p><b>Correo:</b> <?php echo htmlspecialchars($usuario['correo']); ?></p>
        <p><b>Rol:</b> <?php echo ucfirst($usuario['rol']); ?></p>

        <?php if ($usuario['rol'] === 'profesor'): ?>
            <h3>Cursos que dicta</h3>
            <?php if (count($cursos) > 0): ?>
                <ul>
                    <?php foreach ($cursos as $c): ?>
                        <li>Profesor de <?php echo htmlspecialchars($c['curso']); ?> (<?php echo htmlspecialchars($c['materia']); ?>)</li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Todavía no tenés cursos asignados.</p>
            <?php endif; ?>

            <h3>Profesores del mismo curso</h3>
            <?php if (count($companeros) > 0): ?>
                <ul>
                    <?php foreach ($companeros as $p): ?>
                        <li><img src="../Assets/imagenes/<?php echo $p['foto'] ? 'perfiles/' . htmlspecialchars($p['foto']) : 'foto-usuario.png'; ?>" alt="Foto" width="30" height="30"> <?php echo htmlspecialchars($p['nombre'] ?? $p['correo']); ?> - <?php echo htmlspecialchars($p['materia']); ?> (<?php echo htmlspecialchars($p['curso']); ?>)</li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No hay otros profesores en tus cursos.</p>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($usuario['rol'] === 'administrador'): ?>
            <button onclick="window.location.href='../Admin/panel_admin.php'">Volver al panel</button>
        <?php elseif ($usuario['rol'] === 'profesor'): ?>
            <button onclick="window.location.href='../profesor/panel_profesor.php'">Volver al panel</button>
        <?php endif; ?>
        <button onclick="window.location.href='logout.php'">Cerrar sesión</button>
    </main>
    <footer id="footer"></footer>
    <script src="../Assets/main.js"></script>
</body>
</html>
