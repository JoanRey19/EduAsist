<?php
session_start();
require_once "../config/conexion.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$mensaje = "";

// Guardar asistencia
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fecha = $_POST["fecha"];
    $asistencias = $_POST["asistencia"];

    foreach ($asistencias as $id_estudiante => $estado) {
        $stmt = $conexion->prepare("INSERT INTO asistencias (id_estudiante, fecha, estado) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $id_estudiante, $fecha, $estado);
        $stmt->execute();
    }

    $mensaje = "Asistencia registrada correctamente.";
}

// Obtener lista de estudiantes
$resultado = $conexion->query("SELECT * FROM estudiantes");
$estudiantes = $resultado->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Asistencia</title>
    <link rel="stylesheet" href="../css/styles_registrar_asist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <h2>Registrar Asistencia</h2>
        
        <?php if ($mensaje): ?>
            <p class="success-message"><?= $mensaje ?></p>
        <?php endif; ?>

        <form method="post">
            <label>Fecha:
                <input type="date" name="fecha" value="<?= date("Y-m-d") ?>" required>
            </label>

            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Matrícula</th>
                    <th>Asistencia</th>
                </tr>
                <?php foreach ($estudiantes as $estudiante): ?>
                    <tr>
                        <td><?= htmlspecialchars($estudiante["nombre"]) ?></td>
                        <td><?= htmlspecialchars($estudiante["matricula"]) ?></td>
                        <td>
                            <select name="asistencia[<?= $estudiante["id_estudiante"] ?>]" required>
                                <option value="Presente">Presente</option>
                                <option value="Ausente">Ausente</option>
                                <option value="Tarde">Tarde</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <button type="submit"><i class="fa-solid fa-floppy-disk"></i> Guardar Asistencia</button>

            <a class="back-link" href="dashboard.php"><i class="fa-solid fa-arrow-left"></i> Volver al Dashboard</a>
        </form>
    </div>
</body>
</html>