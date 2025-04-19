<?php
session_start();
require_once "../config/conexion.php";

// Redirigir si no hay sesión iniciada
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $matricula = trim($_POST["matricula"]);
    $correo = trim($_POST["correo"]);

    if (!empty($nombre) && !empty($matricula) && !empty($correo)) {
        $sql = "INSERT INTO estudiantes (nombre, matricula, correo) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sss", $nombre, $matricula, $correo);

        if ($stmt->execute()) {
            $mensaje = "✅ Estudiante registrado exitosamente.";
            header("Location: lista_estudiantes.php");
            exit;
        } else {
            $mensaje = "❌ Error al registrar estudiante: " . $stmt->error;
        }
    } else {
        $mensaje = "⚠️ Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Estudiante</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #eef; padding: 40px; }
        .formulario {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; color: #333; }
        label, input { display: block; width: 100%; margin-top: 10px; }
        input[type="submit"] {
            background: #28a745;
            color: white;
            padding: 10px;
            margin-top: 20px;
            cursor: pointer;
            border: none;
        }
        .mensaje { margin-top: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="formulario">
        <h2>Registrar Estudiante</h2>
        <form method="POST" action="registrar_estudiante.php">
            <label>Nombre:</label>
            <input type="text" name="nombre" required>

            <label>Matrícula:</label>
            <input type="text" name="matricula" required>

            <label>Correo:</label>
            <input type="email" name="correo" required>

            <input type="submit" value="Registrar">
        </form>

        <div class="mensaje"><?php echo $mensaje; ?></div>
    </div>
</body>
</html>