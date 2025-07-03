<?php
include 'conexion.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: departamentos.php");
    exit();
}

$id = intval($_GET['id']);

// Verificar si el departamento existe
$sql = "SELECT IDDEPTO FROM departamentos WHERE IDDEPTO = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div style='margin:20px; color: red;'>⚠️ Departamento no encontrado.</div>";
    echo "<a href='departamentos.php' style='margin:20px;' class='btn btn-secondary'>Volver</a>";
    exit();
}

// Si existe, proceder a eliminar
$sql = "DELETE FROM departamentos WHERE IDDEPTO = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: departamentos.php");
exit();

