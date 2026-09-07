<?php
session_start();
require '../login/conexion.php';

// Validar que se hayan enviado los datos requeridos
if (
    empty($_POST['fecha']) || 
    empty($_POST['salon']) || 
    empty($_POST['hora_inicio']) || 
    empty($_POST['hora_fin']) || 
    empty($_POST['profesor']) || 
    empty($_POST['curso'])
) {
    echo "<script>
            alert('Faltan datos para realizar la reserva.');
            window.history.back();
          </script>";
    exit();
}

$fecha = $_POST['fecha'];
$salon = $_POST['salon'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];
$profesor = $_POST['profesor'];
$curso = $_POST['curso'];

// Validar que la hora de inicio sea menor a la de fin
if ($hora_inicio >= $hora_fin) {
    echo "<script>
            alert('La hora de inicio debe ser menor a la hora de finalización.');
            window.history.back();
          </script>";
    exit();
}

try {
    // 1. Verificar si el salón ya está ocupado en ese horario
    $sql_verificar = "SELECT id FROM reservas_salones 
                      WHERE fecha = ? AND salon = ? 
                      AND hora_inicio < ? AND hora_fin > ?";
                      
    $stmt_verificar = $conexion->prepare($sql_verificar);
    $stmt_verificar->execute([$fecha, $salon, $hora_fin, $hora_inicio]);

    if ($stmt_verificar->rowCount() > 0) {
        // Extraemos mes y año para regresar exactamente a ese mes en el calendario
        $partesFecha = explode('-', $fecha);
        $anio = $partesFecha[0];
        $mes = $partesFecha[1];

        echo "<script>
                alert('El salón ya está reservado en ese horario.');
                window.location.href = 'calendario.php?mes=$mes&anio=$anio&fecha=$fecha';
              </script>";
        exit();
    }

    // 2. Insertar la reserva si no hay cruce de horarios
    $sql_insertar = "INSERT INTO reservas_salones (fecha, salon, hora_inicio, hora_fin, profesor, curso) 
                    VALUES (?, ?, ?, ?, ?, ?)";
                    
    $stmt_insertar = $conexion->prepare($sql_insertar);
    $exito = $stmt_insertar->execute([$fecha, $salon, $hora_inicio, $hora_fin, $profesor, $curso]);

    if ($exito) {
        $partesFecha = explode('-', $fecha);
        $anio = $partesFecha[0];
        $mes = $partesFecha[1];

        echo "<script>
                alert('Reserva guardada con éxito.');
                window.location.href = '../panel_profesor/panel_profesor.php';
              </script>";
        exit();
    }

} catch (PDOException $e) {
    echo "Error al procesar la reserva: " . $e->getMessage();
}
?>