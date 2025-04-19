<?php
session_start();
require_once "../config/conexion.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}


$fechas = $conexion->query("SELECT DISTINCT fecha FROM asistencias ORDER BY fecha DESC");
$estudiantes = $conexion->query("SELECT id_estudiante, nombre FROM estudiantes ORDER BY nombre ASC");

$asistencias = [];
$filtro_aplicado = false;

// Procesar filtros si se han enviado
if ($_SERVER["REQUEST_METHOD"] == "GET" && (isset($_GET["fecha"]) || isset($_GET["estudiante"]))) {
    $filtro_aplicado = true;
    $condiciones = [];
    $parametros = [];
    $tipos = "";

    $query = "SELECT a.fecha, e.nombre AS estudiante, a.estado
              FROM asistencias a
              JOIN estudiantes e ON a.id_estudiante = e.id_estudiante";

    if (!empty($_GET["fecha"])) {
        $condiciones[] = "a.fecha = ?";
        $parametros[] = $_GET["fecha"];
        $tipos .= "s";
    }

    if (!empty($_GET["estudiante"])) {
        $condiciones[] = "e.id_estudiante = ?";
        $parametros[] = $_GET["estudiante"];
        $tipos .= "i";
    }

    if ($condiciones) {
        $query .= " WHERE " . implode(" AND ", $condiciones);
    }

    $query .= " ORDER BY a.fecha DESC";

    $stmt = $conexion->prepare($query);

    if (!empty($parametros)) {
        $stmt->bind_param($tipos, ...$parametros);
    }

    $stmt->execute();
    $resultado = $stmt->get_result();
    $asistencias = $resultado->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Asistencia</title>
    <link rel="stylesheet" href="../css/styles_reportes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <h2><i class="fas fa-chart-bar"></i> Reporte de Asistencia</h2>

    <form method="GET" action="">
        <label for="fecha"><i class="far fa-calendar-alt"></i> Filtrar por fecha:</label>
        <select name="fecha" id="fecha">
            <option value="">-- Todas --</option>
            <?php while ($fila = $fechas->fetch_assoc()): ?>
                <option value="<?= $fila["fecha"] ?>" <?= (isset($_GET["fecha"]) && $_GET["fecha"] === $fila["fecha"]) ? "selected" : "" ?>>
                    <?= $fila["fecha"] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="estudiante"><i class="fas fa-user-graduate"></i> Filtrar por estudiante:</label>
        <select name="estudiante" id="estudiante">
            <option value="">-- Todos --</option>
            <?php while ($fila = $estudiantes->fetch_assoc()): ?>
                <option value="<?= $fila["id_estudiante"] ?>" <?= (isset($_GET["estudiante"]) && $_GET["estudiante"] == $fila["id_estudiante"]) ? "selected" : "" ?>>
                    <?= $fila["nombre"] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit"><i class="fas fa-search"></i> Buscar</button>
    </form>

    <?php if ($filtro_aplicado): ?>
        <h3><i class="fas fa-filter"></i> Resultados:</h3>
        <?php if (!empty($asistencias)): ?>
            <table>
                <thead>
                    <tr>
                        <th><i class="far fa-calendar"></i> Fecha</th>
                        <th><i class="fas fa-user-graduate"></i> Estudiante</th>
                        <th><i class="fas fa-clipboard-check"></i> Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($asistencias as $a): ?>
                        <tr>
                            <td><?= $a["fecha"] ?></td>
                            <td><?= $a["estudiante"] ?></td>
                            <td>
                                <?php if ($a["estado"] == 'Presente'): ?>
                                    <i class="fas fa-check-circle" style="color: #2ecc71;"></i>
                                <?php elseif ($a["estado"] == 'Ausente'): ?>
                                    <i class="fas fa-times-circle" style="color: #e74c3c;"></i>
                                <?php else: ?>
                                    <i class="fas fa-question-circle" style="color: #f39c12;"></i>
                                <?php endif; ?>
                                <?= $a["estado"] ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-results"><i class="fas fa-exclamation-circle"></i> No hay registros que coincidan con los filtros seleccionados.</p>
        <?php endif; ?>
    <?php endif; ?>

    <a href="dashboard.php" class="back-link"><i class="fas fa-arrow-left"></i> Volver al dashboard</a>
</body>
</html>
