<?php
session_start();

// 1. Verificamos si existe una sesión activa y si el rol está definido
if (!isset($_SESSION['rol'])) {
    // Si no está logueado, lo mandamos al login
    header("Location: ../login/login.php"); 
    exit();
}

// 2. Verificamos que el rol sea exclusivamente 'admin' o 'profesor'
$rol_usuario = $_SESSION['rol'];
if ($rol_usuario !== 'administrador' && $rol_usuario !== 'profesor') {
    // Si es un usuario con otro rol (ej. alumno), lo redirigimos o mostramos error
    echo "<script>
            alert('No tienes permisos para acceder a esta página.');
            window.location.href = '../login/login.php';
          </script>";
    exit();
}

// Si pasa las validaciones, el código continúa normalmente...
require '../login/conexion.php';

// ... resto de tu código del calendario (variables de fecha, etc.)

$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
$anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');

$fechaSeleccionada = isset($_GET['fecha']) ? $_GET['fecha'] : null;

$fechaActual = sprintf("%04d-%02d-01", $anio, $mes);

$primerDia = (int)date('N', strtotime($fechaActual));
$diasMes = (int)date('t', strtotime($fechaActual));

$fechaAnterior = strtotime("$fechaActual -1 month");
$mesAnterior = date('m', $fechaAnterior);
$anioAnterior = date('Y', $fechaAnterior);

$fechaSiguiente = strtotime("$fechaActual +1 month");
$mesSiguiente = date('m', $fechaSiguiente);
$anioSiguiente = date('Y', $fechaSiguiente);

$nombreMes = [
    1 => "Enero",
    2 => "Febrero",
    3 => "Marzo",
    4 => "Abril",
    5 => "Mayo",
    6 => "Junio",
    7 => "Julio",
    8 => "Agosto",
    9 => "Septiembre",
    10 => "Octubre",
    11 => "Noviembre",
    12 => "Diciembre"
];

?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Calendario de Reservas</title>

<link rel="stylesheet" href="calendario.css">

</head>

<body>

<div class="contenedor">

<h1>📅 Reserva de Salones</h1>

<div class="calendario">

    <!-- CABECERA DEL CALENDARIO -->

    <div class="cabecera-calendario">

        <a href="?mes=<?php echo $mesAnterior; ?>&anio=<?php echo $anioAnterior; ?>">
            ←
        </a>

        <h2>
            <?php echo $nombreMes[$mes] . " " . $anio; ?>
        </h2>

        <a href="?mes=<?php echo $mesSiguiente; ?>&anio=<?php echo $anioSiguiente; ?>">
            →
        </a>

    </div>


    <!-- DÍAS DE LA SEMANA -->

    <div class="dias-semana">

        <div>Lun</div>
        <div>Mar</div>
        <div>Mié</div>
        <div>Jue</div>
        <div>Vie</div>
        <div>Sáb</div>
        <div>Dom</div>

    </div>

<!-- DÍAS DEL MES -->
<div class="dias">
    <?php
    // Espacios vacíos antes del primer día
    for ($i = 1; $i < $primerDia; $i++) {
        echo "<div></div>";
    }

    // Días del mes
    for ($dia = 1; $dia <= $diasMes; $dia++) {
        $fecha = sprintf("%04d-%02d-%02d", $anio, $mes, $dia);
        
        // Usamos mktime para evitar cualquier error de zona horaria
        $timestamp = mktime(0, 0, 0, $mes, $dia, $anio);
        $diaSemana = (int)date('N', $timestamp); 
        
        // 6 = Sábado, 7 = Domingo
        if ($diaSemana === 6 || $diaSemana === 7) {
            echo "<div class='dia deshabilitado'>$dia</div>";
        } else {
            $clase = ($fecha === $fechaSeleccionada) ? "dia seleccionado" : "dia";
            echo "<a class='$clase' href='nueva_reserva.php?fecha=$fecha'>$dia</a>";
        }
    }
    ?>
</div>

</div>

</div>

</body>

</html>