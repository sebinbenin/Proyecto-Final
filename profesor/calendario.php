<?php

session_start();
require '../login/conexion.php';

$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
$anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');

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

        for ($i = 1; $i < $primerDia; $i++) {

            echo "<div></div>";

        }


        for ($dia = 1; $dia <= $diasMes; $dia++) {

            $fecha = sprintf(
                "%04d-%02d-%02d",
                $anio,
                $mes,
                $dia
            );

            echo "
            <a
                class='dia'
                href='reservas_dias.php?fecha=$fecha'
            >
                $dia
            </a>
            ";

        }

        ?>

    </div>

</div>

</div>

</body>

</html>

