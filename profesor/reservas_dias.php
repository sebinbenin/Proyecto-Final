<?php

session_start();

require '../login/conexion.php';


// Verificar que se recibió una fecha

if (!isset($_GET['fecha']) || empty($_GET['fecha'])) {

    header("Location: calendario.php");
    exit();

}


$fecha = $_GET['fecha'];


// Buscar las reservas del día

$sql = "SELECT salon, hora_inicio, hora_fin, profesor, curso
        FROM reservas_salones
        WHERE fecha = :fecha
        ORDER BY hora_inicio";


$stmt = $conexion->prepare($sql);


$stmt->execute([
    ':fecha' => $fecha
]);


$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reservas del día</title>

    <link rel="stylesheet" href="calendario.css">

</head>


<body>

<div class="contenedor">


    <a class="volver" href="calendario.php">

        ← Volver al calendario

    </a>


    <h1>

        Reservas del día

    </h1>


    <h2>

        <?php echo htmlspecialchars($fecha); ?>

    </h2>


    <br>


    <a
        class="boton-reservar"
        href="nueva_reserva.php?fecha=<?php echo urlencode($fecha); ?>"
    >

        + Nueva reserva

    </a>


    <table>


        <thead>

            <tr>

                <th>Salón</th>

                <th>Hora de inicio</th>

                <th>Hora de finalización</th>

                <th>Reservado por</th>

                <th>Curso</th>

            </tr>

        </thead>


        <tbody>


        <?php if (count($resultado) > 0) { ?>


            <?php foreach ($resultado as $fila) { ?>


                <tr>

                    <td>

                        <?php echo htmlspecialchars($fila['salon']); ?>

                    </td>


                    <td>

                        <?php echo htmlspecialchars($fila['hora_inicio']); ?>

                    </td>


                    <td>

                        <?php echo htmlspecialchars($fila['hora_fin']); ?>

                    </td>


                    <td>

                        <?php echo htmlspecialchars($fila['profesor']); ?>

                    </td>


                    <td>

                        <?php echo htmlspecialchars($fila['curso']); ?>

                    </td>

                </tr>


            <?php } ?>


        <?php } else { ?>


            <tr>

                <td colspan="5">

                    No hay reservas para este día.

                </td>

            </tr>


        <?php } ?>


        </tbody>


    </table>


</div>


</body>

</html>