<?php
session_start();
require 'conexion.php';
$mensaje = "";
$enviado = false;

// Devuelve lo que el usuario había escrito, para no perderlo si hay un error
function valor($campo) {
    return htmlspecialchars($_POST[$campo] ?? '');
}

// Deja la cédula con el formato 1.234.567-8 (acepta que la escriban con o sin puntos y guion)
function formatear_cedula($texto) {
    $digitos = preg_replace('/\D/', '', $texto);
    if (strlen($digitos) < 7 || strlen($digitos) > 8) {
        return null;
    }
    $numero = substr($digitos, 0, -1);
    return number_format((int) $numero, 0, '', '.') . '-' . substr($digitos, -1);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = ($_POST['rol'] ?? '') === 'profesor' ? 'profesor' : 'alumno';
    $cedula = null;
    $fecha_nacimiento = null;

    // Profesores y alumnos nacen pendientes hasta que un administrador los apruebe
    $estado = 'pendiente';

    if ($rol === 'alumno') {
        $cedula = formatear_cedula($_POST['cedula'] ?? '');
        $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
        if ($cedula === null) {
            $mensaje = "La cédula debe tener 7 u 8 números (ej: 1.234.567-8).";
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_nacimiento) || $fecha_nacimiento >= date('Y-m-d')) {
            $mensaje = "Ingresá una fecha de nacimiento válida.";
        }
    }

    if ($mensaje === "") {
        $sql = "INSERT INTO usuarios (nombre, correo, cedula, fecha_nacimiento, password, rol, estado) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);

        try {
            $stmt->execute([$nombre, $correo, $cedula, $fecha_nacimiento, $password_hash, $rol, $estado]);
            $enviado = true;
        } catch (PDOException $e) {
            $mensaje = "No se pudo enviar la solicitud: el correo o la cédula ya están registrados.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
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
            <section class="login-section">
                <h2>Bienvenido</h2>
                <?php if ($enviado): ?>
                    <div class="aviso aviso-ok">
                        <b>¡Solicitud enviada!</b><br>
                        Tu registro se envió al administrador. Vas a poder iniciar sesión cuando apruebe tu cuenta.
                    </div>
                    <p class="form-text"><a href="login.php">Ir a iniciar sesión</a></p>
                <?php else: ?>
                <?php if ($mensaje !== ""): ?>
                    <div class="aviso aviso-error"><?php echo htmlspecialchars($mensaje); ?></div>
                <?php endif; ?>
                <form action="registro.php" method="POST">
                    <input type="name" placeholder="Nombre completo" name="nombre" value="<?php echo valor('nombre'); ?>" required><br><br>
                    <input type="email" placeholder="Correo electrónico" name="correo" value="<?php echo valor('correo'); ?>" required><br><br>
                    <input type="password" placeholder="Contraseña" name="password" required><br><br>
                    <!-- Solo para alumnos -->
                    <div id="datos-alumno">
                        <input type="text" placeholder="Cédula (ej: 1.234.567-8)" name="cedula" id="cedula" inputmode="numeric" value="<?php echo valor('cedula'); ?>" required><br><br>
                        <label class="form-text" for="fecha_nacimiento">Fecha de nacimiento</label><br>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" max="<?php echo date('Y-m-d'); ?>" value="<?php echo valor('fecha_nacimiento'); ?>" required><br><br>
                    </div>
                    <select placeholder="Rol" name="rol" id="rol" required>
                        <option value="alumno">Alumno</option>
                        <option value="profesor" <?php if (($_POST['rol'] ?? '') === 'profesor') echo 'selected'; ?>>Profesor</option>
                    </select>
                    <button type="submit">Registrarse</button> <br><br>
                    <p class="form-text">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
                    <p class="form-text">Completa el formulario para registrarte. Tu cuenta será revisada por un administrador antes de poder iniciar sesión.</p>
                </form>
                <?php endif; ?>
                <a href="../pagina_principal/PagPrincipal.php"><i class="fa-solid fa-chevron-left"></i></a>
            </section>
        </div>
        <section class="right-section">

        </section>
    </main>
    <script src="login.js"></script>
    <script>
        // Muestra cédula y fecha de nacimiento solo cuando el rol es alumno
        const rol = document.getElementById("rol");
        const datosAlumno = document.getElementById("datos-alumno");
        function actualizarCampos() {
            const esAlumno = rol.value === "alumno";
            datosAlumno.style.display = esAlumno ? "" : "none";
            datosAlumno.querySelectorAll("input").forEach(input => input.required = esAlumno);
        }
        if (rol) {
            rol.addEventListener("change", actualizarCampos);
            actualizarCampos();
        }
    </script>
</body>
</html>
