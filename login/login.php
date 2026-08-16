<?php
session_start();
require 'conexion.php';
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $sql = "SELECT id, password, rol, estado FROM usuarios WHERE correo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    // ... tu consulta sql se mantiene arriba
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario['password'])) {
        
        //Filtro de aprobaciones
        if ($usuario['estado'] === 'pendiente') {
            $mensaje = "Tu cuenta aún no ha sido aprobada.";
        } elseif ($usuario['estado'] === 'rechazado') {
            $mensaje = "Tu solicitud ha sido rechazada.";
        } else {
            // El estado es 'aprobado', iniciamos sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['rol'] = $usuario['rol'];
            
            // Redirigir al inicio o a su panel
            if ($usuario['rol'] === 'administrador') {
                header("Location: ../Admin/panel_admin.php");
            } elseif ($usuario['rol'] === 'profesor') {
                header("Location: ../profesor/momentaneo.php");
            } else {
                header("Location: ../pagina_principal/PagPrincipal.html");
            }
            exit();
        }
    } else {
        $mensaje = "Correo o contraseña incorrectos.";
    }
}

include 'header.php';
?>

<main>
    <h2>Iniciar Sesión</h2>
    <p style="color: red;"><b><?php echo $mensaje; ?></b></p>

    <form action="login.php" method="POST">
        <label>Correo:</label><br>
        <input type="email" name="correo" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Entrar</button>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </form>
</main>
</body>
</html>