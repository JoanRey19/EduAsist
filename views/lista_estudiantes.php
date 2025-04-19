<?php
session_start();
require_once "../config/conexion.php";

// Verifica si hay sesión iniciada
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

// Consulta estudiantes
$sql = "SELECT * FROM estudiantes";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Estudiantes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f8; padding: 30px; }
        table { width: 80%; margin: auto; border-collapse: collapse; background: white; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #007bff; color: white; }
        h2 { text-align: center; color: #333; }
        .volver { display: block; text-align: center; margin-top: 20px; }
        a.button {
            background: #28a745; color: white; padding: 10px 15px;
            text-decoration: none; border-radius: 5px;
        }
    </style>
</head>
<body>
    <h2>Lista de Estudiantes</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Matrícula</th>
            <th>Correo</th>
            <th>Acciones</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $fila["id_estudiante"]; ?></td>
            <td><?php echo htmlspecialchars($fila["nombre"]); ?></td>
            <td><?php echo htmlspecialchars($fila["matricula"]); ?></td>
            <td><?php echo htmlspecialchars($fila["correo"]); ?></td>
            <td class="actions">
                <a href="editar_estudiante.php?id=<?php echo $fila['id_estudiante']; ?>">
                    <i class="fas fa-edit"></i> Editar
                </a> |
                <a href="../Utilities/eliminar_estudiante.php?id=<?php echo $fila['id_estudiante']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este estudiante?');">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <div class="volver">
        <a href="dashboard.php" class="button">
            <i class="fas fa-arrow-left"></i> Volver al Dashboard
        </a>
    </div>
</body>
</html>