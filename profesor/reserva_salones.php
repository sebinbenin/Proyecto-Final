<?php
session_start();

// Validar inicio de sesión
if (!isset($_SESSION['rol'])) {
    header("Location: ../login/login.php");
    exit();
}

require '../login/conexion.php';

// Obtener la fecha seleccionada
$fecha = (isset($_GET['fecha']) && !empty($_GET['fecha'])) ? $_GET['fecha'] : date('Y-m-d');
// No se puede reservar un día que ya pasó
if ($fecha < date('Y-m-d')) {
    $fecha = date('Y-m-d');
}

// Autocompletar con el nombre guardado en la sesión
$profesor_logueado = $_SESSION['usuario_nombre'] ?? '';
$rol_usuario = $_SESSION['rol'];
$es_alumno = $rol_usuario === 'alumno';

// Los alumnos solo pueden reservar estos salones (con fines de estudio)
$salones_alumno = ['Biblioteca', 'Anfiteatro'];

// Para el alumno, el motivo se completa con su clase
$curso_alumno = '';
if ($es_alumno) {
    $stmt = $conexion->prepare("SELECT cursos.nombre FROM usuarios INNER JOIN cursos ON usuarios.curso_id = cursos.id WHERE usuarios.id = ?");
    $stmt->execute([$_SESSION['usuario_id']]);
    $curso_alumno = $stmt->fetchColumn() ?: '';
}

$paneles = [
    'administrador' => '../Admin/panel_admin.php',
    'profesor' => '../profesor/panel_profesor.php',
    'alumno' => '../alumno/panel_alumno.php'
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Salones</title>
    <link rel="stylesheet" href="calendario.css">
</head>
<body>

<div class="contenedor formulario">

    <!-- VOLVER -->
    <a class="volver" href="<?php echo $paneles[$rol_usuario] ?? '../login/login.php'; ?>">← Volver</a>

    <h1>Nueva reserva de salón</h1>

    <?php include 'aviso.php'; ?>

    <form action="guardar_reserva.php" method="POST">

        <!-- FECHA -->
        <label for="fecha">Fecha:</label>
        <input
            type="date"
            id="fecha"
            name="fecha"
            value="<?php echo htmlspecialchars($fecha); ?>"
            min="<?php echo date('Y-m-d'); ?>"
            required
        >

        <!-- SALÓN (Cargado dinámicamente desde la BD) -->
        <label for="salon">Salón:</label>
        <select id="salon" name="salon" required>
            <option value="">Seleccionar salón</option>
            <?php
            try {
                $query_salones = $conexion->query("SELECT DISTINCT nombre FROM salones ORDER BY nombre ASC");
                while ($salon_item = $query_salones->fetch(PDO::FETCH_ASSOC)) {
                    if ($es_alumno && !in_array($salon_item['nombre'], $salones_alumno)) {
                        continue;
                    }
                    echo '<option value="' . htmlspecialchars($salon_item['nombre']) . '">'
                         . htmlspecialchars($salon_item['nombre'])
                         . '</option>';
                }
            } catch (PDOException $e) {
                echo '<option value="">Error al cargar salones</option>';
            }
            ?>
        </select>

        <!-- HORA DE INICIO -->
        <label for="hora_inicio">Hora de inicio:</label>
        <input
            type="time"
            id="hora_inicio"
            name="hora_inicio"
            required
        >

        <!-- HORA DE FINALIZACIÓN -->
        <label for="hora_fin">Hora de finalización:</label>
        <input
            type="time"
            id="hora_fin"
            name="hora_fin"
            required
        >

        <!-- PROFESOR / ALUMNO -->
        <label for="profesor">Reservado por:</label>
        <input
            type="text"
            id="profesor"
            name="profesor"
            placeholder="<?php echo $es_alumno ? 'Tu nombre' : 'Nombre del profesor'; ?>"
            value="<?php echo htmlspecialchars($profesor_logueado); ?>"
            <?php if ($es_alumno) echo 'readonly'; ?>
            required
        >

        <!-- CURSO / MOTIVO -->
        <label for="curso"><?php echo $es_alumno ? 'Motivo:' : 'Curso / Clase:'; ?></label>
        <input
            type="text"
            id="curso"
            name="curso"
            placeholder="<?php echo $es_alumno ? 'Ejemplo: Estudio para el parcial de Matemática' : 'Ejemplo: 3° Informática'; ?>"
            value="<?php echo $es_alumno ? htmlspecialchars(trim('Estudio ' . $curso_alumno)) : ''; ?>"
            required
        >

        <!-- BOTÓN -->
        <button type="submit" >Reservar salón</button>

    </form>

</div>

</body>
</html>
