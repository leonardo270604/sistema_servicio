<?php
include 'conexion.php';

$id = $_GET['id'];

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevo_nombre = $_POST['DESCRIPCION'];

    $sql = "UPDATE departamentos SET DESCRIPCION = ? WHERE IDDEPTO = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nuevo_nombre, $id);
    $stmt->execute();

    header("Location: departamentos.php");
    exit();
}

// Obtener datos actuales
$sql = "SELECT DESCRIPCION FROM departamentos WHERE IDDEPTO = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$row = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Departamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Editar Departamento</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="DESCRIPCION" class="form-label">Nombre del Departamento</label>
            <input type="text" name="DESCRIPCION" id="DESCRIPCION" class="form-control" value="<?= $row['DESCRIPCION'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="departamentos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
