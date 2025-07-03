<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['tipo_servicio'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $id_tipo_servicio = $_POST['tipo_servicio'];
    $fecha_hora = date('Y-m-d H:i:s');

    $sql = "INSERT INTO servicios (id_usuario, id_tipo_servicio, fecha_hora, estado) 
            VALUES (?, ?, ?, 'pendiente')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $id_usuario, $id_tipo_servicio, $fecha_hora);
    $stmt->execute();
}

header("Location: servicio.php");
exit();
