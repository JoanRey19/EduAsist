<?php
include("../config/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_DEFAULT);

    // Verificar si el correo ya existe
    $verificar = $conexion->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $verificar->bind_param("s", $correo);
    $verificar->execute();
    $resultado = $verificar->get_result();

    if ($resultado->num_rows > 0) {
        $mensaje = "Ya existe un usuario con ese correo.";
    } else {
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, contrasena) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $correo, $contrasena);

        if ($stmt->execute()) {
           echo "<script>alert(''Usuario registrado correctamente');</script>";
           header("Location: login.php");
           exit(); 

        }  else {
            $mensaje = "Error al registrar el usuario.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="../css/styles_registro.css">
</head>
<body>
    <form method="POST" action="registro.php">
    <h2>Registro de Usuario</h2>
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Correo:</label><br>
        <input type="email" name="correo" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="contrasena" required><br><br>

        <input type="submit" value="Registrar">
    </form>

    <?php if (isset($mensaje)) echo "<p style='color:blue;'>$mensaje</p>"; ?>
</body>
</html>