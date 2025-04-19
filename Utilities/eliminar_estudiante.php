<?php
session_start();
require_once "../config/conexion.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);

    $sql = "DELETE FROM estudiantes WHERE id_estudiante = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../views/lista_estudiantes.php");
    } else {
        echo "Error al eliminar el estudiante.";
    }
} else {
    echo "ID de estudiante no proporcionado.";
}
?>