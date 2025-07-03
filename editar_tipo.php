<?php
include 'conexion.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevo_nombre = $_POST['nombre_servicio'];

    $sql = "UPDATE tiposervicio SET nombre_servicio = ? WHERE id_tipo_servicio = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nuevo_nombre, $id);
    $stmt->execute();

    header("Location: tiposervicio.php");
    exit();
}

// Obtener datos actuales
$sql = "SELECT nombre_servicio FROM tiposervicio WHERE id_tipo_servicio = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$row = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Tipo de Servicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Editar Tipo de Servicio</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="nombre_servicio" class="form-label">Nombre del Tipo</label>
            <input type="text" name="nombre_servicio" id="id_tipo_servicio" class="form-control" value="<?= $row['nombre_servicio'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="tiposervicio.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
