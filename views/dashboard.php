<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>EduAsist - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .menu {
            margin-top: 20px;
        }
        .menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
            background-color: #007BFF;
            color: white;
            padding: 12px;
            border-radius: 5px;
            text-decoration: none;
            width: 250px;
        }
        .menu a:hover {
            background-color: #0056b3;
        }
        .logout {
            color: white;
            background-color: #dc3545 !important;
            margin-top: 30px;
        }
        .logout:hover {
            background-color: #bb2d3b !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION["usuario"]); ?> <i class="fas fa-hand-wave"></i></h1>
        <p>¿Qué deseas hacer hoy?</p>

        <div class="menu">
            <a href="registrar_estudiante.php"><i class="fas fa-user-plus"></i> Registrar Estudiante</a>
            <a href="lista_estudiantes.php"><i class="fas fa-list"></i> Ver Lista de Estudiantes</a>
            <a href="registrar_asistencia.php"><i class="fas fa-clock"></i> Registrar Asistencia</a>
            <a href="reportes_asistencia.php"><i class="fas fa-chart-bar"></i> Ver Reportes de Asistencia</a>
            <a class="logout" href="../Utilities/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
        </div>
    </div>
</body>
</html>