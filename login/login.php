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
                header("Location: ../profesor/panel_profesor.php");
            exit();
            } else {
                header("Location: ../pagina_principal/PagPrincipal.php");
            }
            exit();
        }
    } else {
        $mensaje = "Correo o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css" integrity="sha512-QeR2VH+lsBE5LSAe1Q5EnTBbe7XTBubt8dG93Y7gidSgdMCr8nVqKcfKAMyN96SV8KDbZVTDXChatu5G2KQGzg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel= "stylesheet" href="login.css">
    <link rel="stylesheet" href="../Assets/main.css">
</head>
<body>
    <main>
        <button class="dark-mode-btn" id="darkModeToggle"><i class="fa-solid fa-moon"></i></button>
        <div class="left-section">
            <section class="login-section">
                <h2 id="login-title">Bienvenido</h2>
                <p style="color: red;"><b><?php echo $mensaje; ?></b></p>
                    <form action="login.php" method="POST">
                        <input type="email" placeholder="Correo electrónico" name="correo" required><br><br>
                        <input type="password" placeholder="Contraseña" name="password" required><br><br>
                        <button type="submit">Entrar</button>
                        <p class="form-text">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
                    </form>
                    <a href="../pagina_principal/PagPrincipal.php"><i class="fa-solid fa-chevron-left"></i></a>
            </section>
        </div>
        <section class="right-section">
            
        </section>
    </main>
    <script src="login.js"></script>
</body>
</html>