<?php

session_start();

require '../login/conexion.php';


// Obtener la fecha seleccionada

if (isset($_GET['fecha']) && !empty($_GET['fecha'])) {

    $fecha = $_GET['fecha'];

} else {

    $fecha = date('Y-m-d');

}

?>

<!DOCTYPE html>

<html lang="es">

<head>

```
<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Nueva reserva</title>

<link rel="stylesheet" href="calendario.css">
```

</head>

<body>

<div class="contenedor formulario">

```
<!-- VOLVER -->

<a
    class="volver"
    href="reservas_dia.php?fecha=<?php echo urlencode($fecha); ?>"
>

    ← Volver a reservas

</a>


<h1>🏫 Nueva reserva de salón</h1>


<form action="guardar_reserva.php" method="POST">


    <!-- FECHA -->

    <label for="fecha">
        Fecha:
    </label>

    <input
        type="date"
        id="fecha"
        name="fecha"
        value="<?php echo htmlspecialchars($fecha); ?>"
        required
    >


    <!-- SALÓN -->

    <label for="salon">
        Salón:
    </label>

    <select
        id="salon"
        name="salon"
        required
    >

        <option value="">
            Seleccionar salón
        </option>

        <option value="Salón 1">
            Salón 1
        </option>

        <option value="Salón 2">
            Salón 2
        </option>

        <option value="Salón 3">
            Salón 3
        </option>

        <option value="Salón 4">
            Salón 4
        </option>

    </select>


    <!-- HORA DE INICIO -->

    <label for="hora_inicio">
        Hora de inicio:
    </label>

    <input
        type="time"
        id="hora_inicio"
        name="hora_inicio"
        required
    >


    <!-- HORA DE FINALIZACIÓN -->

    <label for="hora_fin">
        Hora de finalización:
    </label>

    <input
        type="time"
        id="hora_fin"
        name="hora_fin"
        required
    >


    <!-- PROFESOR -->

    <label for="profesor">
        Reservado por:
    </label>

    <input
        type="text"
        id="profesor"
        name="profesor"
        placeholder="Nombre del profesor"
        required
    >


    <!-- CURSO -->

    <label for="curso">
        Curso / Clase:
    </label>

    <input
        type="text"
        id="curso"
        name="curso"
        placeholder="Ejemplo: 3° Informática"
        required
    >


    <!-- BOTÓN -->

    <button type="submit">
        Reservar salón
    </button>


</form>
```

</div>

</body>

</html>
