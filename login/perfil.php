<?php
session_start();
if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit(); }
require 'conexion.php';
$id = $_SESSION['usuario_id'];

$stmt = $conexion->prepare("SELECT usuarios.nombre, correo, rol, foto, cedula, curso_id, cursos.nombre AS curso,
    TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad
    FROM usuarios LEFT JOIN cursos ON usuarios.curso_id = cursos.id WHERE usuarios.id = ?");
$stmt->execute([$id]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);

// Profesores de la clase del alumno
$profesores_alumno = [];
if ($u['rol'] === 'alumno' && $u['curso_id']) {
    $stmt = $conexion->prepare("SELECT usuarios.nombre, usuarios.correo, usuarios.foto, GROUP_CONCAT(materia ORDER BY materia SEPARATOR ', ') AS materia
        FROM profesor_curso INNER JOIN usuarios ON profesor_id = usuarios.id
        WHERE profesor_curso.curso_id = ? AND usuarios.estado = 'aprobado'
        GROUP BY usuarios.id, usuarios.nombre, usuarios.correo, usuarios.foto
        ORDER BY materia");
    $stmt->execute([$u['curso_id']]);
    $profesores_alumno = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$stmt = $conexion->prepare("SELECT cursos.nombre AS curso, materia FROM profesor_curso INNER JOIN cursos ON curso_id = cursos.id WHERE profesor_id = ?");
$stmt->execute([$id]);
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conexion->prepare("SELECT usuarios.nombre, usuarios.correo, usuarios.foto, cursos.nombre AS curso, materia
    FROM profesor_curso INNER JOIN usuarios ON profesor_id = usuarios.id INNER JOIN cursos ON profesor_curso.curso_id = cursos.id
    WHERE profesor_curso.curso_id IN (SELECT pc.curso_id FROM profesor_curso pc WHERE pc.profesor_id = ?) AND profesor_id != ? AND usuarios.estado = 'aprobado'
    ORDER BY cursos.nombre, usuarios.nombre");
$stmt->execute([$id, $id]);
$companeros = $stmt->fetchAll(PDO::FETCH_ASSOC);

function foto($f) { return '../Assets/imagenes/' . ($f ? 'perfiles/' . htmlspecialchars($f) : 'foto-usuario.png'); }
$panel = ['administrador' => '../Admin/panel_admin.php', 'profesor' => '../profesor/panel_profesor.php', 'alumno' => '../alumno/panel_alumno.php'][$u['rol']] ?? '../pagina_principal/PagPrincipal.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css" integrity="sha512-QeR2VH+lsBE5LSAe1Q5EnTBbe7XTBubt8dG93Y7gidSgdMCr8nVqKcfKAMyN96SV8KDbZVTDXChatu5G2KQGzg==" crossorigin="anonymous" referrerpolicy="no-referrer">
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
                <?php if ($u['rol'] === 'alumno'): ?>
                    <div><span>Cédula</span><b><?= htmlspecialchars($u['cedula'] ?? '-') ?></b></div>
                    <div><span>Edad</span><b><?= $u['edad'] !== null ? (int) $u['edad'] . ' años' : '-' ?></b></div>
                <?php endif; ?>
            </div>

            <?php if ($u['rol'] === 'alumno'): ?>
                <h3>Mi clase</h3>
                <?php if ($u['curso']): ?>
                    <ul class="chips"><li><?= htmlspecialchars($u['curso']) ?></li></ul>
                <?php else: ?>
                    <p class="vacio">Todavía no tenés una clase asignada.</p>
                <?php endif; ?>

                <h3>Mis profesores</h3>
                <ul class="lista">
                    <?php foreach ($profesores_alumno as $p): ?>
                        <li><img src="<?= foto($p['foto']) ?>" alt="Foto"><div><b><?= htmlspecialchars($p['nombre'] ?? $p['correo']) ?></b><small><?= htmlspecialchars($p['materia']) ?></small></div></li>
                    <?php endforeach; ?>
                </ul>
                <?php if (!$profesores_alumno): ?><p class="vacio">No hay profesores asignados a tu clase.</p><?php endif; ?>
            <?php endif; ?>

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
