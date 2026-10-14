<?php
session_start();
if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit(); }
require 'conexion.php';
$id = $_SESSION['usuario_id'];

$stmt = $conexion->prepare("SELECT nombre, correo, rol, foto FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conexion->prepare("SELECT cursos.nombre AS curso, materia FROM profesor_curso INNER JOIN cursos ON curso_id = cursos.id WHERE profesor_id = ?");
$stmt->execute([$id]);
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conexion->prepare("SELECT usuarios.nombre, usuarios.correo, usuarios.foto, cursos.nombre AS curso, materia
    FROM profesor_curso INNER JOIN usuarios ON profesor_id = usuarios.id INNER JOIN cursos ON curso_id = cursos.id
    WHERE curso_id IN (SELECT curso_id FROM profesor_curso WHERE profesor_id = ?) AND profesor_id != ? AND usuarios.estado = 'aprobado'
    ORDER BY cursos.nombre, usuarios.nombre");
$stmt->execute([$id, $id]);
$companeros = $stmt->fetchAll(PDO::FETCH_ASSOC);

function foto($f) { return '../Assets/imagenes/' . ($f ? 'perfiles/' . htmlspecialchars($f) : 'foto-usuario.png'); }
$panel = ['administrador' => '../Admin/panel_admin.php', 'profesor' => '../profesor/panel_profesor.php'][$u['rol']] ?? '../pagina_principal/PagPrincipal.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/main.css">
    <link rel="stylesheet" href="perfil.css">
    <script src="../Assets/tema.js"></script>
    <title>Mi Perfil</title>
</head>
<body>
    <header id="header"></header>
    <main class="perfil">
        <div class="banner"></div>
        <img src="<?= foto($u['foto']) ?>" alt="Foto de perfil" class="foto">
        <h1><?= htmlspecialchars($u['nombre'] ?? 'Sin nombre') ?></h1>
        <span class="rol"><?= ucfirst($u['rol']) ?></span>

        <form action="subir_foto.php" method="POST" enctype="multipart/form-data">
            <label for="foto"> Elige tu foto</label>
            <input type="file" id="foto" name="foto" accept="image/*" required onchange="archivo.textContent = this.files[0]?.name">
            <button type="submit">Cambiar foto</button>
            <small id="archivo">Ningún archivo seleccionado</small>
        </form>
        <?php if (isset($_GET['foto'])): ?>
            <p class="error">No se pudo subir la foto. Usá una imagen JPG, PNG o WEBP de hasta 2 MB.</p>
        <?php endif; ?>

        <div class="cuerpo">
            <div class="datos">
                <div><span>Nombre</span><b><?= htmlspecialchars($u['nombre'] ?? 'Sin nombre') ?></b></div>
                <div><span>Correo</span><b><?= htmlspecialchars($u['correo']) ?></b></div>
            </div>

            <?php if ($u['rol'] === 'profesor'): ?>
                <h3>Cursos que dicta</h3>
                <ul class="chips">
                    <?php foreach ($cursos as $c): ?><li><?= htmlspecialchars($c['curso'] . ' · ' . $c['materia']) ?></li><?php endforeach; ?>
                </ul>
                <?php if (!$cursos): ?><p class="vacio">Todavía no tenés cursos asignados.</p><?php endif; ?>

                <h3>Profesores del mismo curso</h3>
                <ul class="lista">
                    <?php foreach ($companeros as $p): ?>
                        <li><img src="<?= foto($p['foto']) ?>" alt="Foto"><div><b><?= htmlspecialchars($p['nombre'] ?? $p['correo']) ?></b><small><?= htmlspecialchars($p['materia'] . ' · ' . $p['curso']) ?></small></div></li>
                    <?php endforeach; ?>
                </ul>
                <?php if (!$companeros): ?><p class="vacio">No hay otros profesores en tus cursos.</p><?php endif; ?>
            <?php endif; ?>

            <div class="botones">
                <button onclick="location.href='<?= $panel ?>'">Volver al panel</button>
                <button class="salir" onclick="location.href='logout.php'">Cerrar sesión</button>
            </div>
        </div>
    </main>
    <footer id="footer"></footer>
    <script src="../Assets/main.js"></script>
</body>
</html>
