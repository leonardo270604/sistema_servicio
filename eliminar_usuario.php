<?php
include 'conexion.php';
$id = $_GET['id'];
$conn->query("DELETE FROM usuarios WHERE id_usuario = $id");
header("Location: usuarios.php");
?>
