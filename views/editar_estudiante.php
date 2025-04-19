<?php
session_start();
require_once "../config/conexion.php";


if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}


$id = $_GET["id"] ?? null;

if ($id && is_numeric($id)) {
    $stmt = $conexion->prepare("SELECT * FROM estudiantes WHERE id_estudiante = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $estudiante = $resultado->fetch_assoc();

    if (!$estudiante) {
        die("Estudiante no encontrado.");
    }
} else {
    die("ID no válido.");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $matricula = $_POST["matricula"];
    $correo = $_POST["correo"];
    $id_post = $_POST["id"] ?? null;

    if (!$id_post || !is_numeric($id_post)) {
        die("ID no válido para actualización.");
    }

    $sql = "UPDATE estudiantes SET nombre=?, matricula=?, correo=? WHERE id_estudiante=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $matricula, $correo, $id_post);

    if ($stmt->execute()) {
        header("Location: ../views/lista_estudiantes.php");
        exit;
    } else {
        echo "Error al actualizar el estudiante.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Estudiante</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        
        h2 {
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
            font-weight: 600;
        }
        
        form {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #34495e;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="hidden"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        button[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        
        button[type="submit"]:hover {
            background-color: #2980b9;
        }
        
        @media (max-width: 600px) {
            form {
                padding: 20px;
            }
            
            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <h2>Editar Estudiante</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($estudiante['id_estudiante']); ?>">

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($estudiante['nombre']); ?>" required>

        <label for="matricula">Matrícula:</label>
        <input type="text" name="matricula" value="<?php echo htmlspecialchars($estudiante['matricula']); ?>" required>

        <label for="correo">Correo:</label>
        <input type="email" name="correo" value="<?php echo htmlspecialchars($estudiante['correo']); ?>" required>

        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>