<?php
session_start();
require 'conexion.php';
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];
    
    // Si es profesor, nace pendiente. Si no, nace aprobado.
    $estado = ($rol === 'profesor') ? 'pendiente' : 'aprobado';

    $sql = "INSERT INTO usuarios (correo, password, rol, estado) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    
    try {
        $stmt->execute([$correo, $password_hash, $rol, $estado]);
        if ($rol === 'profesor') {
            $mensaje = "Registro exitoso. Tu cuenta está en evaluación por un administrador.";
        } else {
            $mensaje = "Registro exitoso. Ya puedes iniciar sesión.";
        }
    } catch (PDOException $e) {
        $mensaje = "Error: El correo ya existe o hubo un problema.";
    }
}

// Incluimos el diseño después de la lógica
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
    <link rel= "stylesheet" href="login.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <main>
        <h2>Crear cuenta</h2>
        <p><b><?php echo $mensaje; ?></b></p>

        <form action="registro.php" method="POST">
            <label>Correo:</label><br>
            <input type="email" name="correo" required><br><br>

            <label>Contraseña:</label><br>
            <input type="password" name="password" required><br><br>

            <label>Rol:</label><br>
            <select name="rol" required>
                <option value="alumno">Alumno</option>
                <option value="profesor">Profesor</option>
            </select><br><br>

            <button type="submit">Registrarse</button>
        </form>
   </main>
</body>
</html>