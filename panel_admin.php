<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
    
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel de Administración</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            width: 280px;
            background-color: #343a40;
            color: white;
            height: 100vh;
            padding: 30px 20px;
            position: fixed;
        }
        .sidebar h2 {
            margin-top: 0;
            font-size: 22px;
            margin-bottom: 30px;
        }
        .sidebar ul {
            list-style-type: none;
            padding-left: 0;
        }
        .sidebar li {
            margin-bottom: 15px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            display: block;
            padding: 10px;
            border-radius: 5px;
            background-color: #495057;
            transition: background 0.3s;
        }
        .sidebar a:hover {
            background-color: #17a2b8;
        }
        .main {
            margin-left: 300px;
            padding: 40px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Bienvenido <?= $_SESSION['usuario'] ?></h2>
    <ul>
        <li><a href="servicios_pendientes.php">Servicios Pendientes</a></li>
        <li><a href="usuarios.php">Usuarios</a></li>
        <li><a href="tiposervicio.php">Tipos de Servicio</a></li>
        <li><a href="departamentos.php">Departamentos</a></li>
        <li><a href="historial.php">Historial</a></li>
        <li><a href="login.php">Cerrar Sesión</a></li>
    </ul>
</div>

<div class="main">
    <h1>Panel de Administración</h1>
    <p></p>
</div>
</body>
</html>
