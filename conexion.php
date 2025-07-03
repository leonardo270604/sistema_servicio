<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "sistema_servicio";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

date_default_timezone_set('America/Mexico_City');

}
?>
