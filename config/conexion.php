<?php
$host = "localhost";
$user = "root";
$passw = "";
$db_name = "asistencia_db"; 

$conexion = new mysqli($host, $user, $passw, $db_name);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>