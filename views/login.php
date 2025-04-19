<?php
include("../config/conexion.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];

    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($contrasena, $usuario["contrasena"])) {
            $_SESSION["id_usuario"] = $usuario["id_usuario"];
            $_SESSION["usuario"] = $usuario["nombre"];
            $_SESSION["correo"] = $usuario["correo"];
            header("Location: dashboard.php");
            exit;
        } else {
            $mensaje = "Contraseña incorrecta.";
        }
    } else {
        $mensaje = "Correo no registrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Asistencia</title>
    <link rel="stylesheet" href="../css/styles_login.css">
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <form method="POST" action="login.php" class="login-form">
            <label>Correo:</label>
            <input type="email" name="correo" required>

            <label>Contraseña:</label>
            <input type="password" name="contrasena" required>

            <input type="submit" value="Iniciar sesión" class="btn">
        </form>

        <?php if (isset($mensaje)) echo "<p class='mensaje-error'>$mensaje</p>"; ?>

        <div class="registro-link">
            <p>¿No tienes una cuenta?</p>
            <a href="registro.php" class="btn-registro">Registrarse</a>
        </div>
    </div>
</body>
</html>