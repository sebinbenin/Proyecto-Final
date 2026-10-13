<?php
session_start();
require 'conexion.php';
$mensaje = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];
    
    // Si es profesor, nace pendiente. Si no, nace aprobado.
    $estado = ($rol === 'profesor') ? 'pendiente' : 'aprobado';

    $sql = "INSERT INTO usuarios (nombre, correo, password, rol, estado) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    
    try {
        $stmt->execute([$nombre, $correo, $password_hash, $rol, $estado]);
        if ($rol === 'profesor') {
            echo "<script type='text/javascript'>alert('Registro exitoso. Tu cuenta está en evaluación por un administrador.');</script>";
        } else {
            echo "<script type='text/javascript'>alert('Registro exitoso. Ya puedes iniciar sesión.');</script>";
        }
    } catch (PDOException $e) {
        echo "<script type='text/javascript'>alert('Error: El correo ya existe o hubo un problema.');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <link rel= "stylesheet" href="login.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css" integrity="sha512-QeR2VH+lsBE5LSAe1Q5EnTBbe7XTBubt8dG93Y7gidSgdMCr8nVqKcfKAMyN96SV8KDbZVTDXChatu5G2KQGzg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel ="stylesheet" href = "login.css">
    <link rel="stylesheet" href="../Assets/main.css">
</head>
<body>
    <main>
        <button class="dark-mode-btn" id="darkModeToggle"><i class="fa-solid fa-moon"></i></button>
        <div class="left-section">
            <p><b><?php echo $mensaje; ?></b></p>
            <section class="login-section">
                <h2>Bienvenido</h2>
                <form action="registro.php" method="POST">
                    <input type="name" placeholder="Nombre completo" name="nombre" required><br><br>
                    <input type="email" placeholder="Correo electrónico" name="correo" required><br><br>
                    <input type="password" placeholder="Contraseña" name="password" required><br><br>
                    <select placeholder="Rol" name="rol" required>
                        <option value="alumno">Alumno</option>
                        <option value="profesor">Profesor</option>
                    </select>
                    <button type="submit">Registrarse</button> <br><br>
                    <p class="form-text">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
                    <p class="form-text">Completa el formulario para registrarte. Si eres profesor, tu cuenta será revisada por un administrador antes de poder iniciar sesión.</p>
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