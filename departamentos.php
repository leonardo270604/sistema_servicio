<?php
include 'conexion.php';

// Consulta los departamentos
$sql = "SELECT IDDEPTO, DESCRIPCION FROM departamentos";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Departamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include 'menu_lateral.php'; ?>

<div style="margin-left: 250px; padding: 20px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Departamentos</h2>
        <a href="agregar_departamento.php" class="btn btn-success">Agregar departamento</a>
    </div>

    <table class="table table-bordered table-hover table-striped align-middle">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Nombre del Departamento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['IDDEPTO'] ?></td>
                    <td><?= $row['DESCRIPCION'] ?></td>
                    <td>
                    <a href="editar_departamento.php?id=<?= $row['IDDEPTO'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="eliminar_departamento.php?id=<?= $row['IDDEPTO'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este departamento?')">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
