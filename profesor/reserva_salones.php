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

// Autocompletar con el nombre guardado en la sesión
$profesor_logueado = $_SESSION['usuario_nombre'] ?? '';
$rol_usuario = $_SESSION['rol'];
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
    <a class="volver" href="<?php if ($rol_usuario === 'profesor') {
            echo "../profesor/panel_profesor.php";
        }
        elseif ($rol_usuario === 'administrador') {
            echo "../Admin/panel_admin.php";
        } ?>">← Volver</a>

    <h1>Nueva reserva de salón</h1>

    <form action="guardar_reserva.php" method="POST">

        <!-- FECHA -->
        <label for="fecha">Fecha:</label>
        <input
            type="date"
            id="fecha"
            name="fecha"
            value="<?php echo htmlspecialchars($fecha); ?>"
            required
        >

        <!-- SALÓN (Cargado dinámicamente desde la BD) -->
        <label for="salon">Salón:</label>
        <select id="salon" name="salon" required>
            <option value="">Seleccionar salón</option>
            <?php
            try {
                $query_salones = $conexion->query("SELECT nombre FROM salones ORDER BY nombre ASC");
                while ($salon_item = $query_salones->fetch(PDO::FETCH_ASSOC)) {
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

        <!-- PROFESOR -->
        <label for="profesor">Reservado por:</label>
        <input
            type="text"
            id="profesor"
            name="profesor"
            placeholder="Nombre del profesor"
            value="<?php echo htmlspecialchars($profesor_logueado); ?>"
            required
        >

        <!-- CURSO -->
        <label for="curso">Curso / Clase:</label>
        <input
            type="text"
            id="curso"
            name="curso"
            placeholder="Ejemplo: 3° Informática"
            required
        >

        <!-- BOTÓN -->
        <button type="submit" >Reservar salón</button>

    </form>

</div>

</body>
</html>