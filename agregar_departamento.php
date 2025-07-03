<?php
include 'conexion.php';

$mensaje_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['DESCRIPCION']);

    // Validar campo vacío
    if (empty($nombre)) {
        $mensaje_error = "⚠️ El nombre del departamento no puede estar vacío.";
    } else {
        // Verificar si ya existe
        $sql_check = "SELECT * FROM departamentos WHERE DESCRIPCION = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $nombre);
        $stmt_check->execute();
        $resultado = $stmt_check->get_result();

        if ($resultado->num_rows > 0) {
            $mensaje_error = "⚠️ Ya existe un departamento con ese nombre.";
        } else {
            // Insertar nuevo departamento
            $sql = "INSERT INTO departamentos (DESCRIPCION) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $nombre);
            $stmt->execute();

            header("Location: departamentos.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar Departamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Agregar Departamento</h2>

    <?php if (!empty($mensaje_error)): ?>
        <div class="alert alert-warning"><?= $mensaje_error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="DESCRIPCION" class="form-label">Nombre del Departamento</label>
            <input type="text" name="DESCRIPCION" id="DESCRIPCION" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="departamentos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
