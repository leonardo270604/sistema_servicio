<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre_servicio'];

    $sql = "INSERT INTO tiposervicio (nombre_servicio) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nombre);
    $stmt->execute();

    header("Location: tiposervicio.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar Tipo de Servicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Agregar servicio</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="nombre_servicio" class="form-label">Nombre del servicio </label>
            <input type="text" name="nombre_servicio" id="id_tipo_servicio" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="tiposervicio.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
